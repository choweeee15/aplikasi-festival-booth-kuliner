@extends('layouts.app')
@section('title','Kelola Penyewa')

@section('dashboard-css')
<style>
  .dashboard-container{padding:30px;background:linear-gradient(135deg,#4e0000,#800000);min-height:100vh;color:#f8f8f8;animation:fadeInScale 1s ease forwards}
  @keyframes fadeInScale{0%{opacity:0;transform:scale(.95)}100%{opacity:1;transform:scale(1)}}
  .title-heading{font-size:2rem;font-weight:700;color:#f8f8f8}
  .card-dark{background-color:#5a0000;border-radius:12px;border:none;color:#fff;box-shadow:0 8px 20px rgb(255 0 0 /.2)}
  .table{background:#fff;color:#000;border-radius:8px;overflow:hidden;box-shadow:0 8px 20px rgb(255 0 0 /.2)}
  .table thead th{background:linear-gradient(45deg,#7b0000,#b30000);color:#fff;font-weight:700;border:none}
  .table tbody tr:hover{background:#ffe6e6}
  .btn-success{background:linear-gradient(45deg,#218838,#28a745);border:none}
  .btn-warning{background:#ffc107;color:#212529;border:none}
  .btn-danger{background:#dc3545;border:none}
</style>
@endsection

@section('content')
<div class="dashboard-container">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="title-heading"><i class="fas fa-user-tie me-2"></i> Kelola Penyewa</h2>
    <button class="btn btn-success btn-sm shadow-sm" data-bs-toggle="modal" data-bs-target="#modalCreate">
      <i class="fas fa-plus me-1"></i> Tambah Penyewa
    </button>
  </div>

  @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
  @if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif

  <div class="card card-dark mb-3">
    <div class="card-body">
      <form class="row g-2" method="GET" action="{{ route('penyewas.index') }}">
        <div class="col-md-8">
          <div class="input-group">
            <span class="input-group-text bg-dark text-white border-0"><i class="fas fa-search"></i></span>
            <input type="text" class="form-control" name="q" placeholder="Cari nama/email/HP..." value="{{ request('q') }}">
          </div>
        </div>
        <div class="col-md-2">
          @php $pp = (int) (request('per_page') ?? 10); @endphp
          <select name="per_page" class="form-select">
            @foreach([10,20,50,100] as $n)
              <option value="{{ $n }}" {{ $pp===$n ? 'selected' : '' }}>{{ $n }}/hal</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-2 d-grid"><button class="btn btn-light text-dark fw-semibold">Terapkan</button></div>
      </form>
    </div>
  </div>

  <div class="card card-dark">
    <div class="card-body p-3">
      <div class="table-responsive">
        <table class="table table-hover table-striped align-middle text-center">
          <thead>
            <tr>
              <th>No</th>
              <th>Nama</th>
              <th>Email</th>
              <th>No HP</th>
              <th>Alamat</th>
              <th width="150">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($penyewas as $i=>$p)
            <tr>
              <td>{{ method_exists($penyewas,'firstItem') ? $penyewas->firstItem()+$i : $i+1 }}</td>
              <td class="text-start">{{ $p->nama }}</td>
              <td class="text-start">{{ $p->email }}</td>
              <td>{{ $p->no_hp }}</td>
              <td class="text-start">{{ \Illuminate\Support\Str::limit($p->alamat, 60) }}</td>
              <td>
                <button
                  class="btn btn-sm btn-warning me-1 btn-edit"
                  data-bs-toggle="modal"
                  data-bs-target="#modalEdit"
                  data-id="{{ $p->id }}"
                  data-nama="{{ $p->nama }}"
                  data-email="{{ $p->email }}"
                  data-no_hp="{{ $p->no_hp }}"
                  data-alamat="{{ $p->alamat }}"
                ><i class="fas fa-edit"></i></button>

                <form action="{{ route('penyewas.destroy',$p->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus penyewa ini?')">
                  @csrf @method('DELETE')
                  <button class="btn btn-sm btn-danger"><i class="fas fa-trash-alt"></i></button>
                </form>
              </td>
            </tr>
            @empty
            <tr><td colspan="6" class="text-center text-muted">Belum ada data.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
      @if(method_exists($penyewas,'links'))
        <div class="mt-3">{{ $penyewas->appends(request()->query())->links() }}</div>
      @endif
    </div>
  </div>
</div>

{{-- Modal Create --}}
<div class="modal fade" id="modalCreate" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form action="{{ route('penyewas.store') }}" method="POST">
      @csrf
      <div class="modal-content bg-dark text-white">
        <div class="modal-header"><h5 class="modal-title">Tambah Penyewa</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
        <div class="modal-body">
          <div class="mb-3"><label>Nama</label><input type="text" name="nama" class="form-control" required></div>
          <div class="mb-3"><label>Email</label><input type="email" name="email" class="form-control" required></div>
          <div class="mb-3"><label>No HP</label><input type="text" name="no_hp" class="form-control"></div>
          <div class="mb-3"><label>Alamat</label><textarea name="alamat" class="form-control" rows="3"></textarea></div>
        </div>
        <div class="modal-footer"><button class="btn btn-success">Simpan</button><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button></div>
      </div>
    </form>
  </div>
</div>

{{-- Modal Edit (REUSABLE) --}}
<div class="modal fade" id="modalEdit" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form id="formEdit" method="POST">
      @csrf @method('PUT')
      <div class="modal-content bg-dark text-white">
        <div class="modal-header"><h5 class="modal-title">Edit Penyewa</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
        <div class="modal-body">
          <div class="mb-3"><label>Nama</label><input id="edit_nama" type="text" name="nama" class="form-control" required></div>
          <div class="mb-3"><label>Email</label><input id="edit_email" type="email" name="email" class="form-control" required></div>
          <div class="mb-3"><label>No HP</label><input id="edit_no_hp" type="text" name="no_hp" class="form-control"></div>
          <div class="mb-3"><label>Alamat</label><textarea id="edit_alamat" name="alamat" class="form-control" rows="3"></textarea></div>
        </div>
        <div class="modal-footer"><button class="btn btn-success">Simpan</button><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button></div>
      </div>
    </form>
  </div>
</div>
@endsection

@section('dashboard-js')
<script>
  document.addEventListener('click', function(e){
    if(e.target.closest('.btn-edit')){
      const btn = e.target.closest('.btn-edit');
      const id = btn.dataset.id;
      const form = document.getElementById('formEdit');
      form.action = "{{ url('/admin/penyewas') }}/" + id;

      document.getElementById('edit_nama').value = btn.dataset.nama || '';
      document.getElementById('edit_email').value = btn.dataset.email || '';
      document.getElementById('edit_no_hp').value = btn.dataset.no_hp || '';
      document.getElementById('edit_alamat').value = btn.dataset.alamat || '';
    }
  });
</script>
@endsection
