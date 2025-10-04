@extends('layouts.main')
@section('title', 'Pembelian Tiket')

@section('content')
<div class="container-xxl py-5">
  <div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h2 class="mb-0">Pembelian Tiket</h2>
        <small class="text-muted">Lihat riwayat pembelian tiket kamu.</small>
      </div>
      <a href="{{ route('pembelian-tiket.create') }}" class="btn btn-danger">
        <i class="fas fa-plus me-1"></i> Beli Tiket
      </a>
    </div>

    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if(session('error'))   <div class="alert alert-danger">{{ session('error') }}</div> @endif

    {{-- Filter by email --}}
    <form method="GET" action="{{ route('pembelian-tiket.index') }}" class="row g-2 mb-4">
      <div class="col-md-6">
        <input type="email" name="email" class="form-control" placeholder="Masukkan email kamu" value="{{ $email }}">
      </div>
      <div class="col-md-3 d-grid">
        <button class="btn btn-outline-danger"><i class="fas fa-search me-1"></i> Tampilkan</button>
      </div>
    </form>

    <div class="card shadow border-0">
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0 align-middle text-center">
            <thead>
              <tr>
                <th>#</th>
                <th>Tiket</th>
                <th>Jumlah</th>
                <th>Total</th>
                <th>Status Bayar</th>
                <th>Tanggal</th>
              </tr>
            </thead>
            <tbody>
              @forelse($items as $i => $row)
              <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ optional($row->tiket)->nama_tiket ?? $row->tiket_id }}</td>
                <td>{{ $row->jumlah }}</td>
                <td>Rp {{ number_format($row->total_harga, 0, ',', '.') }}</td>
                <td><span class="badge bg-{{ $row->status_bayar == 'lunas' ? 'success' : 'warning' }}">{{ ucfirst($row->status_bayar) }}</span></td>
                <td>{{ \Carbon\Carbon::parse($row->created_at)->format('d M Y') }}</td>
              </tr>
              @empty
              <tr>
                <td colspan="6" class="text-muted">Belum ada data / masukkan email kamu di filter.</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div>
</div>
@endsection
