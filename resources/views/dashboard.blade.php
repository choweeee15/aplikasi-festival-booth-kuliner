@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('dashboard-css')
<style>
    .dashboard-container {
        padding: 30px;
        background: linear-gradient(135deg, #4e0000, #800000);
        min-height: 100vh;
        color: #f8f8f8;
    }
    .stat-card {
        border-radius: 12px;
        box-shadow: 0 8px 25px rgba(255, 0, 0, 0.3);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        padding: 22px 18px;
        color: #fff;
        height: 100%;
    }
    .stat-card:hover { transform: translateY(-6px); box-shadow: 0 15px 40px rgba(255,0,0,0.5); }
    .stat-icon { font-size: 2.2rem; opacity: .9; }
    .stat-title { font-weight: 700; letter-spacing: .03em; margin: 8px 0 4px; }
    .stat-value { font-weight: 900; font-size: 2.2rem; line-height: 1; text-shadow: 0 0 5px rgba(255,0,0,.5); }

    .bg-primary  { background: linear-gradient(45deg, #7b0000, #b30000); }
    .bg-success  { background: linear-gradient(45deg, #a30000, #d10000); }
    .bg-info     { background: linear-gradient(45deg, #8b0000, #cc0000); }
    .bg-warning  { background: linear-gradient(45deg, #6b0000, #9f0000); }
    .bg-danger   { background: linear-gradient(45deg, #b20000, #ff0000); }
    .bg-secondary{ background: linear-gradient(45deg, #4e0000, #800000); }

    .card-dark {
        background: #5a0000; color: #fff; border: none; border-radius: 12px;
        box-shadow: 0 8px 25px rgba(255, 0, 0, 0.25);
    }
    .table { background:#fff; color:#000; border-radius:8px; overflow:hidden; }
    .table thead th { background: linear-gradient(45deg,#7b0000,#b30000); color:#fff; border:none; }
    .badge-soft { background: rgba(255,255,255,.15); border:1px solid rgba(255,255,255,.2); }
</style>
@endsection

@section('content')
<div class="dashboard-container">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h2 class="mb-0">Dashboard Admin Festival 🎪</h2>
        <span class="badge badge-soft px-3 py-2">Ringkasan Operasional</span>
    </div>

    {{-- BARIS 1: BOOTH & TIKET --}}
    <div class="row g-4">
        <div class="col-md-3">
            <div class="stat-card bg-primary">
                <div class="stat-icon"><i class="fas fa-store"></i></div>
                <div class="stat-title">Total Booth</div>
                <div class="stat-value">{{ $totalBooth }}</div>
                <small>Tersedia: {{ $boothTersedia }} • Disewa: {{ $boothDisewa }}</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card bg-success">
                <div class="stat-icon"><i class="fas fa-ticket-alt"></i></div>
                <div class="stat-title">Jenis Tiket</div>
                <div class="stat-value">{{ $totalJenisTiket }}</div>
                <small>Tiket terjual: {{ $tiketTerjual }}</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card bg-info">
                <div class="stat-icon"><i class="fas fa-money-bill-wave"></i></div>
                <div class="stat-title">Pendapatan Tiket</div>
                <div class="stat-value">Rp {{ number_format($pendapatanTiket, 0, ',', '.') }}</div>
                <small>Status bayar diakui: lunas</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card bg-warning">
                <div class="stat-icon"><i class="fas fa-handshake"></i></div>
                <div class="stat-title">Transaksi Sewa</div>
                <div class="stat-value">{{ $sewaDisetujui }}</div>
                <small>Pending: {{ $sewaPending }} • Ditolak: {{ $sewaDitolak }}</small>
            </div>
        </div>
    </div>

    {{-- BARIS 2: PENGGUNA --}}
    <div class="row g-4 mt-1">
        <div class="col-md-3">
            <div class="stat-card bg-danger">
                <div class="stat-icon"><i class="fas fa-user-tie"></i></div>
                <div class="stat-title">Penyewa Booth</div>
                <div class="stat-value">{{ $totalPenyewa }}</div>
                <small>Terdaftar</small>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card bg-secondary">
                <div class="stat-icon"><i class="fas fa-users"></i></div>
                <div class="stat-title">Pengunjung</div>
                <div class="stat-value">{{ $totalPengunjung }}</div>
                <small>Terdaftar</small>
            </div>
        </div>
    </div>

    {{-- RINGKASAN TABEL --}}
    <div class="row g-4 mt-1">
        <div class="col-lg-6">
            <div class="card card-dark">
                <div class="card-body">
                    <h5 class="mb-3"><i class="fas fa-receipt me-2"></i>Pembelian Tiket Terbaru</h5>
                    <div class="table-responsive">
                        <table class="table table-sm align-middle text-center">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Pengunjung ID</th>
                                    <th>Tiket ID</th>
                                    <th>Jumlah</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($recentPembelian as $row)
                                <tr>
                                    <td>{{ $row->id }}</td>
                                    <td>{{ $row->pengunjung_id }}</td>
                                    <td>{{ $row->tiket_id }}</td>
                                    <td>{{ $row->jumlah }}</td>
                                    <td>Rp {{ number_format($row->total_harga,0,',','.') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $row->status_bayar=='lunas'?'success':'warning' }}">
                                            {{ ucfirst($row->status_bayar) }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="6" class="text-muted">Belum ada data.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <a href="{{ route('pembelian-tiket.index') }}" class="btn btn-outline-light btn-sm">Lihat semua</a>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card card-dark">
                <div class="card-body">
                    <h5 class="mb-3"><i class="fas fa-handshake me-2"></i>Transaksi Sewa Terbaru</h5>
                    <div class="table-responsive">
                        <table class="table table-sm align-middle text-center">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Penyewa ID</th>
                                    <th>Booth ID</th>
                                    <th>Mulai</th>
                                    <th>Selesai</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($recentSewa as $row)
                                <tr>
                                    <td>{{ $row->id }}</td>
                                    <td>{{ $row->penyewa_id }}</td>
                                    <td>{{ $row->booth_id }}</td>
                                    <td>{{ $row->tanggal_mulai }}</td>
                                    <td>{{ $row->tanggal_selesai }}</td>
                                    <td>Rp {{ number_format($row->total_bayar,0,',','.') }}</td>
                                    <td>
                                        <span class="badge bg-{{ $row->status=='disetujui'?'success':($row->status=='ditolak'?'danger':'warning') }}">
                                            {{ ucfirst($row->status) }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="7" class="text-muted">Belum ada data.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <a href="{{ route('transaksi-sewa.index') }}" class="btn btn-outline-light btn-sm">Lihat semua</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('dashboard-js')
<script>
    AOS && AOS.init({ once:true, easing:'ease-in-out', duration:700 });
</script>
@endsection
