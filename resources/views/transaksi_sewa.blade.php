@extends('layouts.app')
@section('title','Transaksi Sewa Booth')
@section('dashboard-css')
<style>.dashboard-container{padding:30px;background:linear-gradient(135deg,#4e0000,#800000);min-height:100vh;color:#f8f8f8}
.title-heading{font-size:2rem;font-weight:700;color:#f8f8f8}.card-dark{background:#5a0000;border-radius:12px;border:none;color:#fff;box-shadow:0 8px 20px rgb(255 0 0 / .2)}
.table{background:#fff;color:#000;border-radius:8px;overflow:hidden;box-shadow:0 8px 20px rgb(255 0 0 / .2)}.table thead th{background:linear-gradient(45deg,#7b0000,#b30000);color:#fff;border:none}
.btn-success{background:linear-gradient(45deg,#218838,#28a745);border:none}.btn-warning{background:#ffc107;border:none;color:#212529}.btn-danger{background:#dc3545;border:none}
</style>
@endsection
@section('content')
<div class="dashboard-container">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="title-heading"><i class="fas fa-handshake me-2"></i> Transaksi Sewa Booth</h2>
    <button class="btn btn-success btn-sm shadow-sm" data-bs-toggle="modal" data-bs-target="#addModal"><i class="fas fa-plus me-1"></i> Tambah Transaksi</button>
  </div>

  @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
  @if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif

  <div class="card card-dark"><div class="card-body p-3">
    <div class="table-responsive">
      <table class="table table-hover table-striped text-center align-middle">
        <thead><tr><th>No</th><th>Penyewa ID</th><th>Booth ID</th><th>Tanggal Mulai</th><th>Tanggal Selesai</th><th>Total Bayar</th><th>Status</th><th>Aksi</th></tr></thead>
        <tbody>
          @forelse($items as $i)
          <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $i->penyewa_id }}</td>
            <td>{{ $i->booth_id }}</td>
            <td>{{ $i->tanggal_mulai }}</td>
            <td>{{ $i->tanggal_selesai }}</td>
            <td>Rp {{ number_format($i->total_bayar,0,',','.') }}</td>
            <td><span class="badge bg-{{ $i->status=='disetujui'?'success':($i->status=='ditolak'?'danger':'warning') }}">{{ $i->status }}</span></td>
            <td>
              <button class="btn btn-sm btn-warning me-1 shadow-sm" data-bs-toggle="modal" data-bs-target="#editModal{{ $i->id }}"><i class="fas fa-edit"></i></button>
              <form action="{{ route('transaksi-sewa.destroy',$i->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus?')">
                @csrf @method('DELETE') <button class="btn btn-sm btn-danger shadow-sm"><i class="fas fa-trash-alt"></i></button>
              </form>
            </td>
          </tr>
          @empty
          <tr><td colspan="8" class="text-center text-muted">Belum ada data.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div></div>
</div>

<div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true"><div class="modal-dialog">
  <form action="{{ route('transaksi-sewa.store') }}" method="POST">@csrf
  <div class="modal-content bg-dark text-white">
    <div class="modal-header"><h5 class="modal-title">Tambah Transaksi</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
    <div class="modal-body">
      <div class="mb-3"><label>Penyewa ID</label><input type="number" name="penyewa_id" class="form-control" required></div>
      <div class="mb-3"><label>Booth ID</label><input type="number" name="booth_id" class="form-control" required></div>
      <div class="mb-3"><label>Tanggal Mulai</label><input type="date" name="tanggal_mulai" class="form-control" required></div>
      <div class="mb-3"><label>Tanggal Selesai</label><input type="date" name="tanggal_selesai" class="form-control" required></div>
      <div class="mb-3"><label>Total Bayar</label><input type="number" step="0.01" name="total_bayar" class="form-control" required></div>
      <div class="mb-3"><label>Status</label>
        <select name="status" class="form-select">
          <option value="pending">Pending</option>
          <option value="disetujui">Disetujui</option>
          <option value="ditolak">Ditolak</option>
        </select>
      </div>
    </div>
    <div class="modal-footer"><button class="btn btn-success">Simpan</button><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button></div>
  </div>
  </form>
</div></div>

@foreach($items as $i)
<div class="modal fade" id="editModal{{ $i->id }}" tabindex="-1" aria-hidden="true"><div class="modal-dialog">
  <form action="{{ route('transaksi-sewa.update',$i->id) }}" method="POST">@csrf @method('PUT')
  <div class="modal-content bg-dark text-white">
    <div class="modal-header"><h5 class="modal-title">Edit Transaksi</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
    <div class="modal-body">
      <div class="mb-3"><label>Penyewa ID</label><input type="number" name="penyewa_id" class="form-control" value="{{ $i->penyewa_id }}" required></div>
      <div class="mb-3"><label>Booth ID</label><input type="number" name="booth_id" class="form-control" value="{{ $i->booth_id }}" required></div>
      <div class="mb-3"><label>Tanggal Mulai</label><input type="date" name="tanggal_mulai" class="form-control" value="{{ $i->tanggal_mulai }}" required></div>
      <div class="mb-3"><label>Tanggal Selesai</label><input type="date" name="tanggal_selesai" class="form-control" value="{{ $i->tanggal_selesai }}" required></div>
      <div class="mb-3"><label>Total Bayar</label><input type="number" step="0.01" name="total_bayar" class="form-control" value="{{ $i->total_bayar }}" required></div>
      <div class="mb-3"><label>Status</label>
        <select name="status" class="form-select">
          <option value="pending" {{ $i->status=='pending'?'selected':'' }}>Pending</option>
          <option value="disetujui" {{ $i->status=='disetujui'?'selected':'' }}>Disetujui</option>
          <option value="ditolak" {{ $i->status=='ditolak'?'selected':'' }}>Ditolak</option>
        </select>
      </div>
    </div>
    <div class="modal-footer"><button class="btn btn-success">Simpan</button><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button></div>
  </div>
  </form>
</div></div>
@endforeach
@endsection
