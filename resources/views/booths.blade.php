@extends('layouts.app')
@section('title','Kelola Booth')
@section('dashboard-css')
<style>
    .dashboard-container{padding:30px;background:linear-gradient(135deg,#4e0000,#800000);min-height:100vh;color:#f8f8f8;animation:fadeInScale 1s ease forwards}
    @keyframes fadeInScale{0%{opacity:0;transform:scale(.95)}100%{opacity:1;transform:scale(1)}}
    .title-heading{font-size:2rem;font-weight:700;color:#f8f8f8}
    .card-dark{background-color:#5a0000;border-radius:12px;border:none;color:white;box-shadow:0 8px 20px rgb(255 0 0 / .2)}
    .table{background:#fff;color:#000;border-radius:8px;overflow:hidden;box-shadow:0 8px 20px rgb(255 0 0 / .2)}
    .table thead th{background:linear-gradient(45deg,#7b0000,#b30000);color:#fff;font-weight:700;border:none;vertical-align:middle}
    .table tbody tr:hover{background:#ffe6e6}
    .table td,.table th{vertical-align:middle}
    .btn-success{background:linear-gradient(45deg,#218838,#28a745);border:none}
    .btn-warning{background:#ffc107;border:none;color:#212529}
    .btn-danger{background:#dc3545;border:none}
</style>
@endsection
@section('content')
<div class="dashboard-container">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="title-heading"><i class="fas fa-store me-2"></i> Kelola Booth</h2>
    <button class="btn btn-success btn-sm shadow-sm" data-bs-toggle="modal" data-bs-target="#addModal">
      <i class="fas fa-plus me-1"></i> Tambah Booth
    </button>
  </div>

  @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
  @if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif

  <div class="card card-dark">
    <div class="card-body p-3">
      <div class="table-responsive">
        <table class="table table-hover table-striped text-center align-middle">
          <thead><tr><th>No</th><th>Nama Booth</th><th>Harga Sewa</th><th>Status</th><th>Aksi</th></tr></thead>
          <tbody>
            @forelse($booths as $booth)
            <tr>
              <td>{{ $loop->iteration }}</td>
              <td>{{ $booth->nama_booth }}</td>
              <td>Rp {{ number_format($booth->harga_sewa,0,',','.') }}</td>
              <td><span class="badge bg-{{ $booth->status=='tersedia'?'success':'secondary' }}">{{ $booth->status }}</span></td>
              <td>
                <button class="btn btn-sm btn-warning me-1 shadow-sm" data-bs-toggle="modal" data-bs-target="#editModal{{ $booth->id }}"><i class="fas fa-edit"></i></button>
                <form action="{{ route('booths.destroy',$booth->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus?')">
                  @csrf @method('DELETE')
                  <button class="btn btn-sm btn-danger shadow-sm"><i class="fas fa-trash-alt"></i></button>
                </form>
              </td>
            </tr>
            @empty
            <tr><td colspan="5" class="text-center text-muted">Belum ada data.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

{{-- Modal Tambah --}}
<div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form action="{{ route('booths.store') }}" method="POST">@csrf
      <div class="modal-content bg-dark text-white">
        <div class="modal-header"><h5 class="modal-title">Tambah Booth</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
        <div class="modal-body">
          <div class="mb-3"><label>Nama Booth</label><input type="text" name="nama_booth" class="form-control" required></div>
          <div class="mb-3"><label>Deskripsi</label><input type="text" name="deskripsi" class="form-control"></div>
          <div class="mb-3"><label>Harga Sewa</label><input type="number" step="0.01" name="harga_sewa" class="form-control" required></div>
          <div class="mb-3"><label>Status</label>
            <select name="status" class="form-select"><option value="tersedia">Tersedia</option><option value="disewa">Disewa</option></select>
          </div>
        </div>
        <div class="modal-footer"><button class="btn btn-success">Simpan</button><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button></div>
      </div>
    </form>
  </div>
</div>

{{-- Modal Edit --}}
@foreach($booths as $booth)
<div class="modal fade" id="editModal{{ $booth->id }}" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form action="{{ route('booths.update',$booth->id) }}" method="POST">@csrf @method('PUT')
      <div class="modal-content bg-dark text-white">
        <div class="modal-header"><h5 class="modal-title">Edit Booth</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
        <div class="modal-body">
          <div class="mb-3"><label>Nama Booth</label><input type="text" name="nama_booth" class="form-control" value="{{ $booth->nama_booth }}" required></div>
          <div class="mb-3"><label>Deskripsi</label><input type="text" name="deskripsi" class="form-control" value="{{ $booth->deskripsi }}"></div>
          <div class="mb-3"><label>Harga Sewa</label><input type="number" step="0.01" name="harga_sewa" class="form-control" value="{{ $booth->harga_sewa }}" required></div>
          <div class="mb-3"><label>Status</label>
            <select name="status" class="form-select">
              <option value="tersedia" {{ $booth->status=='tersedia'?'selected':'' }}>Tersedia</option>
              <option value="disewa" {{ $booth->status=='disewa'?'selected':'' }}>Disewa</option>
            </select>
          </div>
        </div>
        <div class="modal-footer"><button class="btn btn-success">Simpan</button><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button></div>
      </div>
    </form>
  </div>
</div>
@endforeach
@endsection
