@extends('layouts.main')
@section('title', 'Akun Saya')

@section('content')
<div class="container-xxl py-5">
  <div class="container">

    <div class="d-flex align-items-center justify-content-between mb-4">
      <div>
        <h1 class="mb-1">Akun Saya</h1>
        <p class="text-muted mb-0">Ringkasan profil, tiket, dan sewa booth Anda.</p>
      </div>
      <a href="{{ route('user.home') }}" class="btn btn-outline-danger">
        <i class="fas fa-home me-1"></i> Home
      </a>
    </div>

    {{-- Kartu Ringkasan --}}
    <div class="row g-3 mb-4">
      <div class="col-md-4">
        <div class="card border-0 shadow h-100">
          <div class="card-body">
            <div class="d-flex align-items-center">
              <i class="fas fa-receipt fa-2x text-danger me-3"></i>
              <div>
                <div class="small text-muted">Pembelian Tiket</div>
                <div class="fs-4 fw-bold">{{ $summary['total_pembelian'] }}</div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card border-0 shadow h-100">
          <div class="card-body">
            <div class="d-flex align-items-center">
              <i class="fas fa-check-circle fa-2x text-success me-3"></i>
              <div>
                <div class="small text-muted">Tiket Lunas</div>
                <div class="fs-4 fw-bold">{{ $summary['tiket_lunas'] }}</div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card border-0 shadow h-100">
          <div class="card-body">
            <div class="d-flex align-items-center">
              <i class="fas fa-handshake fa-2x text-primary me-3"></i>
              <div>
                <div class="small text-muted">Transaksi Sewa</div>
                <div class="fs-4 fw-bold">{{ $summary['total_sewa'] }}</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- Profil singkat --}}
    <div class="card border-0 shadow mb-4">
      <div class="card-body">
        <h5 class="mb-3"><i class="fas fa-user me-2 text-danger"></i> Profil</h5>
        <div class="row">
          <div class="col-md-6">
            <div class="mb-2"><span class="text-muted small">Nama</span><div class="fw-semibold">{{ $user->name ?? '-' }}</div></div>
            <div class="mb-2"><span class="text-muted small">Email</span><div class="fw-semibold">{{ $user->email ?? '-' }}</div></div>
          </div>
          <div class="col-md-6">
            <div class="mb-2"><span class="text-muted small">Sebagai Pengunjung</span>
              <div class="fw-semibold">
                {{ $pengunjung ? ($pengunjung->nama ?? $pengunjung->email) : 'Belum terdaftar' }}
              </div>
            </div>
            <div class="mb-2"><span class="text-muted small">Sebagai Penyewa</span>
              <div class="fw-semibold">
                {{ $penyewa ? ($penyewa->nama ?? $penyewa->email) : 'Belum terdaftar' }}
              </div>
            </div>
          </div>
        </div>
        {{-- (Opsional) tombol menuju form isi data pengunjung/penyewa --}}
      </div>
    </div>

    {{-- Pembelian Tiket Saya --}}
    <div class="card border-0 shadow mb-4">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h5 class="mb-0"><i class="fas fa-ticket-alt me-2 text-danger"></i> Pembelian Tiket Saya</h5>
          <a href="{{ route('pembelian-tiket.index') }}" class="btn btn-sm btn-danger">
            <i class="fas fa-shopping-cart me-1"></i> Beli Tiket
          </a>
        </div>

        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
              <tr>
                <th>#</th>
                <th>Tiket</th>
                <th>Qty</th>
                <th>Total</th>
                <th>Status</th>
                <th>Tanggal</th>
              </tr>
            </thead>
            <tbody>
              @forelse($pembelian as $i=>$row)
                <tr>
                  <td>{{ $pembelian->firstItem() + $i }}</td>
                  <td>{{ $row->tiket->nama_tiket ?? '—' }}</td>
                  <td>{{ $row->jumlah }}</td>
                  <td>Rp {{ number_format($row->total_harga,0,',','.') }}</td>
                  <td>
                    <span class="badge bg-{{ $row->status_bayar==='lunas'?'success':'warning' }}">
                      {{ ucfirst($row->status_bayar) }}
                    </span>
                  </td>
                  <td>{{ \Carbon\Carbon::parse($row->created_at)->format('d M Y H:i') }}</td>
                </tr>
              @empty
                <tr><td colspan="6" class="text-center text-muted py-4">Belum ada pembelian.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>

        <div class="mt-3">
          {{ $pembelian->links() }}
        </div>
      </div>
    </div>

    {{-- Transaksi Sewa Booth Saya --}}
    <div class="card border-0 shadow">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h5 class="mb-0"><i class="fas fa-store me-2 text-danger"></i> Sewa Booth Saya</h5>
          <a href="{{ route('user.booth.katalog') }}" class="btn btn-sm btn-outline-danger">
            Lihat Katalog Booth
          </a>
        </div>

        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
              <tr>
                <th>#</th>
                <th>Booth</th>
                <th>Periode</th>
                <th>Total Bayar</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              @forelse($sewas as $i=>$t)
                <tr>
                  <td>{{ $sewas->firstItem() + $i }}</td>
                  <td>{{ $t->booth->nama_booth ?? '—' }}</td>
                  <td>{{ \Carbon\Carbon::parse($t->tanggal_mulai)->format('d M Y') }} – {{ \Carbon\Carbon::parse($t->tanggal_selesai)->format('d M Y') }}</td>
                  <td>Rp {{ number_format($t->total_bayar,0,',','.') }}</td>
                  <td>
                    <span class="badge bg-@switch($t->status) @case('disetujui')success @break @case('ditolak')danger @break @default warning @endswitch">
                      {{ ucfirst($t->status) }}
                    </span>
                  </td>
                </tr>
              @empty
                <tr><td colspan="5" class="text-center text-muted py-4">Belum ada transaksi sewa.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>

        <div class="mt-3">
          {{ $sewas->links() }}
        </div>
      </div>
    </div>

  </div>
</div>
@endsection
