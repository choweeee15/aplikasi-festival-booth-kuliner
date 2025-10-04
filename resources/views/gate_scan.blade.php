@extends('layouts.app')
@section('title','Gate Scan')

@section('content')
<div class="dashboard-container">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="title-heading"><i class="fas fa-qrcode me-2"></i> Gate Scan</h2>
    <a href="{{ route('admin.pembelian-tiket.index') }}" class="btn btn-outline-secondary">
      <i class="fas fa-list me-1"></i> Kelola Pembelian
    </a>
  </div>

  @if(isset($result))
    @if($result['ok'])
      <div class="alert alert-success">{{ $result['msg'] }}</div>
    @else
      <div class="alert alert-danger">{{ $result['msg'] }}</div>
    @endif
  @endif

  <div class="card card-dark mb-3">
    <div class="card-body">
      <form method="POST" action="{{ route('gate.scan.verify') }}" class="row g-2">
        @csrf
        <div class="col-12">
          <label class="form-label">Tempel Hasil Scan (atau buka /gate/verify?data=...)</label>
          <textarea name="data" rows="3" class="form-control" placeholder="TCK|TCK-2025...|UID:...|TID:...|SIG:..." required>{{ $data ?? '' }}</textarea>
        </div>
        <div class="col-12 d-grid d-md-block">
          <button class="btn btn-danger"><i class="fas fa-check me-1"></i> Verifikasi</button>
        </div>
      </form>
    </div>
  </div>

  @if(isset($result['row']) && $result['row'])
    @php $row = $result['row']; @endphp
    <div class="card card-dark">
      <div class="card-body">
        <h5 class="mb-3">Detail Tiket</h5>
        <div class="table-responsive">
          <table class="table table-sm align-middle">
            <tr><th>Kode</th><td>{{ $row->kode }}</td></tr>
            <tr><th>Status Bayar</th><td><span class="badge bg-{{ $row->status_bayar==='lunas'?'success':'warning' }}">{{ ucfirst($row->status_bayar) }}</span></td></tr>
            <tr><th>Pengunjung ID</th><td>{{ $row->pengunjung_id }}</td></tr>
            <tr><th>Tiket ID</th><td>{{ $row->tiket_id }}</td></tr>
            <tr><th>Jumlah</th><td>{{ $row->jumlah }}</td></tr>
            <tr><th>Total</th><td>Rp {{ number_format($row->total_harga,0,',','.') }}</td></tr>
            <tr><th>Check-in</th><td>{{ $row->checked_in_at ? \Carbon\Carbon::parse($row->checked_in_at)->format('d M Y H:i') : '-' }}</td></tr>
            <tr><th>Scan Count</th><td>{{ $row->scan_count ?? 0 }}</td></tr>
            <tr><th>Last Scanned</th><td>{{ $row->last_scanned_at ? \Carbon\Carbon::parse($row->last_scanned_at)->format('d M Y H:i') : '-' }}</td></tr>
          </table>
        </div>
      </div>
    </div>
  @endif
</div>
@endsection
