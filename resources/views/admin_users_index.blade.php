@extends('layouts.app')

@section('title', 'Kelola Pengguna')

@section('dashboard-css')
<style>
  .dashboard-container{
    padding:30px;background:linear-gradient(135deg,#4e0000,#800000);
    min-height:100vh;color:#f8f8f8;animation:fadeInScale 1s ease forwards
  }
  @keyframes fadeInScale{0%{opacity:0;transform:scale(.95)}100%{opacity:1;transform:scale(1)}}
  .title-heading{font-size:2rem;font-weight:700;color:#f8f8f8}
  .card-dark{background-color:#5a0000;border-radius:12px;border:none;color:#fff;box-shadow:0 8px 20px rgb(255 0 0 / .2)}
  .toolbar .form-control,.toolbar .form-select{border: none; box-shadow: none}
  .table{background:#fff;color:#000;border-radius:8px;overflow:hidden;box-shadow:0 8px 20px rgb(255 0 0 / .2)}
  .table thead th{background:linear-gradient(45deg,#7b0000,#b30000);color:#fff;font-weight:700;border:none;vertical-align:middle}
  .table tbody tr:hover{background:#ffe6e6}
  .table td,.table th{vertical-align:middle}
  .badge-role{font-weight:700}
  .btn-success{background:linear-gradient(45deg,#218838,#28a745);border:none}
  .btn-warning{background:#ffc107;color:#212529;border:none}
  .btn-danger{background:#dc3545;border:none}
</style>
@endsection

@section('content')
<div class="dashboard-container">
  <div class="d-flex flex-wrap justify-content-between align-items-center mb-4" data-aos="fade-down" data-aos-duration="700" data-aos-delay="200">
    <h2 class="title-heading mb-3 mb-md-0">
      <i class="fas fa-users me-2"></i> Kelola Pengguna
    </h2>
    <div class="d-flex gap-2">
      <button class="btn btn-success btn-sm shadow-sm" data-bs-toggle="modal" data-bs-target="#addUserModal">
        <i class="fas fa-plus me-1"></i> Tambah Pengguna
      </button>
    </div>
  </div>

  {{-- Alerts --}}
  @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
  @if(session('error'))   <div class="alert alert-danger">{{ session('error') }}</div>   @endif
  @if($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
  @endif

  {{-- Toolbar (Cari & Per Page) --}}
  <div class="card card-dark mb-3">
    <div class="card-body">
      <form class="row g-2 toolbar" method="GET" action="{{ route('user.index') }}">
        <div class="col-md-6">
          <div class="input-group">
            <span class="input-group-text bg-dark text-white border-0"><i class="fas fa-search"></i></span>
            <input type="text" class="form-control" name="q" placeholder="Cari nama/email..."
                   value="{{ request('q') }}">
          </div>
        </div>
        <div class="col-md-2">
          <select name="role" class="form-select">
            <option value="">Semua Role</option>
            <option value="user"  {{ request('role')==='user' ? 'selected' : '' }}>User</option>
            <option value="admin" {{ request('role')==='admin'? 'selected' : '' }}>Admin</option>
          </select>
        </div>
        <div class="col-md-2">
          <select name="per_page" class="form-select">
            @php $pp = (int) (request('per_page') ?? 10); @endphp
            @foreach([10,20,50,100] as $n)
              <option value="{{ $n }}" {{ $pp===$n ? 'selected' : '' }}>{{ $n }}/hal</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-2 d-grid">
          <button class="btn btn-light text-dark fw-semibold"><i class="fas fa-filter me-1"></i> Terapkan</button>
        </div>
      </form>
    </div>
  </div>

  {{-- Tabel --}}
  <div class="card card-dark" data-aos="fade-up" data-aos-duration="700" data-aos-delay="300">
    <div class="card-body p-3">
      <div class="table-responsive">
        <table class="table table-hover table-striped text-center align-middle">
          <thead>
          <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Role</th>
            <th>Terdaftar</th>
            <th width="120">Aksi</th>
          </tr>
          </thead>
          <tbody>
          @forelse ($users as $index => $user)
            <tr>
              <td>{{ method_exists($users,'firstItem') ? $users->firstItem() + $index : $index + 1 }}</td>
              <td class="text-start">{{ $user->name }}</td>
              <td class="text-start">{{ $user->email }}</td>
              <td>
                <span class="badge badge-role bg-{{ $user->role==='admin'?'danger':'secondary' }}">
                  {{ ucfirst($user->role) }}
                </span>
              </td>
              <td>{{ optional($user->created_at)->format('d M Y') }}</td>
              <td>
                <button class="btn btn-sm btn-warning me-1 shadow-sm" data-bs-toggle="modal" data-bs-target="#editUserModal{{ $user->id }}">
                  <i class="fas fa-edit"></i>
                </button>
                <form action="{{ route('user.destroy', $user->id) }}" method="POST" class="d-inline"
                      onsubmit="return confirm('Yakin ingin menghapus?')">
                  @csrf
                  @method('DELETE')
                  <button class="btn btn-sm btn-danger shadow-sm"><i class="fas fa-trash-alt"></i></button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="text-center text-muted">Belum ada pengguna.</td>
            </tr>
          @endforelse
          </tbody>
        </table>
      </div>

      {{-- Pagination --}}
      @if(method_exists($users,'links'))
        <div class="mt-3">
          {{ $users->appends(request()->query())->links() }}
        </div>
      @endif
    </div>
  </div>
</div>

{{-- Modal Tambah --}}
<div class="modal fade" id="addUserModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form action="{{ route('user.store') }}" method="POST">
      @csrf
      <div class="modal-content bg-dark text-white">
        <div class="modal-header">
          <h5 class="modal-title">Tambah Pengguna</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label>Nama</label>
            <input type="text" name="name" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
          </div>
          <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" minlength="6" required>
          </div>
          <div class="mb-3">
            <label>Role</label>
            <select name="role" class="form-select" required>
              <option value="user">User</option>
              <option value="admin">Admin</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-success">Simpan</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        </div>
      </div>
    </form>
  </div>
</div>

{{-- Modal Edit --}}
@foreach ($users as $user)
<div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form action="{{ route('user.update', $user->id) }}" method="POST">
      @csrf @method('PUT')
      <div class="modal-content bg-dark text-white">
        <div class="modal-header">
          <h5 class="modal-title">Edit Pengguna</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label>Nama</label>
            <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
          </div>
          <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
          </div>
          <div class="mb-3">
            <label>Password <small class="text-muted">(kosongkan jika tidak diubah)</small></label>
            <input type="password" name="password" class="form-control" minlength="6">
          </div>
          <div class="mb-3">
            <label>Role</label>
            <select name="role" class="form-select" required>
              <option value="user"  {{ $user->role=='user'  ? 'selected' : '' }}>User</option>
              <option value="admin" {{ $user->role=='admin' ? 'selected' : '' }}>Admin</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-success">Simpan</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        </div>
      </div>
    </form>
  </div>
</div>
@endforeach
@endsection
