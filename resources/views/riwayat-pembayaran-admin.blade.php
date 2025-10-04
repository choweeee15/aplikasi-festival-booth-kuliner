@extends('layouts.app')

@section('title', 'Riwayat Pembayaran')

@section('dashboard-css')
<style>
    /* Print styling */
    @media print {
        body * {
            visibility: hidden;
        }
        .table-responsive, .table-responsive * {
            visibility: visible;
        }
        .table-responsive {
            position: absolute;
            left: 0; top: 0;
            width: 100%;
        }
        .sidebar, .header, .footer, h2 {
            display: none;
        }
        .print-header {
            visibility: visible;
            text-align: center;
            margin-bottom: 20px;
        }
        .print-header h3, .print-header p {
            margin: 0;
        }
    }

    /* Container styling mirip dashboard */
    .riwayat-container {
        padding: 30px;
        background: linear-gradient(135deg, #4e0000, #800000);
        min-height: 100vh;
        color: #f8f8f8;
        animation: fadeInScale 1s ease forwards;
    }
    @keyframes fadeInScale {
        0% { opacity: 0; transform: scale(0.95); }
        100% { opacity: 1; transform: scale(1); }
    }

    /* Form styling */
    .filter-form .form-control, .filter-form .btn {
        border-radius: 8px;
    }

    /* Table styling */
    .table {
        background: #fff;
        color: #000;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 8px 20px rgb(255 0 0 / 0.2);
    }
    .table thead th {
        background: linear-gradient(45deg, #7b0000, #b30000);
        color: white;
        font-weight: 700;
        border: none;
    }
    .table tbody tr:hover {
        background: #ffe6e6;
    }

    /* Badge colors */
    .badge-secondary {
        background-color: #6c757d;
    }
    .badge-success {
        background-color: #198754;
    }
    .badge-danger {
        background-color: #dc3545;
    }

    /* Pagination container */
    .pagination-container {
        display: flex;
        justify-content: center;
        margin-top: 30px;
    }
</style>
@endsection

@section('content')
<div class="riwayat-container">
    <h2 class="mb-4" data-aos="fade-down" data-aos-duration="700" data-aos-delay="200">Riwayat Pembayaran Anda</h2>

    <form method="GET" action="{{ route('riwayat.pembayaran.admin') }}" class="row g-3 filter-form mb-5" data-aos="fade-up" data-aos-duration="700" data-aos-delay="300">
        <div class="col-md-2">
            <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}" />
        </div>
        <div class="col-md-2">
            <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}" />
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">Filter</button>
        </div>
        <div class="col-md-2">
            <a href="{{ route('pembayaran.exportPdf', ['start_date' => request('start_date'), 'end_date' => request('end_date')]) }}" class="btn btn-danger w-100">Export PDF</a>
        </div>
        <div class="col-md-2">
            <a href="{{ route('pembayaran.exportExcel', ['start_date' => request('start_date'), 'end_date' => request('end_date')]) }}" class="btn btn-success w-100">Export Excel</a>
        </div>
        <div class="col-md-2">
            <button onclick="window.print()" type="button" class="btn btn-info w-100">Print</button>
        </div>
    </form>

    @if($payments->isEmpty())
        <div class="alert alert-info" data-aos="fade-up" data-aos-duration="700" data-aos-delay="400">Belum ada riwayat pembayaran.</div>
    @else
        <div class="table-responsive" data-aos="fade-up" data-aos-duration="700" data-aos-delay="400">
            <table class="table table-bordered table-hover text-center align-middle">
                <thead>
                    <tr>
                        <th>Tanggal Upload</th>
                        <th>Layanan</th>
                        <th>Nama Kendaraan</th>
                        <th>Model Kendaraan</th>
                        <th>Bukti Pembayaran</th>
                        <th>Status Pembayaran</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($payments as $payment)
                    <tr>
                        <td>{{ $payment->tanggal_upload ? \Carbon\Carbon::parse($payment->tanggal_upload)->format('d M Y H:i') : '-' }}</td>
                        <td>{{ $payment->registrasi->layanan->nama_layanan ?? '-' }}</td>
                        <td>{{ $payment->registrasi->nama_kendaraan ?? '-' }}</td>
                        <td>{{ $payment->registrasi->model_kendaraan ?? '-' }}</td>
                        <td>
                            @if($payment->bukti_pembayaran)
                                <a href="{{ asset('storage/' . $payment->bukti_pembayaran) }}" target="_blank" class="btn btn-sm btn-outline-primary">Lihat Bukti</a>
                            @else
                                <span class="text-muted">Belum ada</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge 
                                bg-{{ match($payment->status) {
                                    'menunggu' => 'secondary',
                                    'diverifikasi' => 'success',
                                    'ditolak' => 'danger',
                                    default => 'light'
                                } }}">
                                {{ ucfirst($payment->status) }}
                            </span>
                        </td>
                        <td>{{ $payment->catatan ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="pagination-container">
                {{ $payments->links('pagination::simple-bootstrap-5') }}
            </div>
        </div>
    @endif
</div>
@endsection

@section('dashboard-js')
<script>
    AOS.init({
        once: true,
        easing: 'ease-in-out',
        duration: 700
    });
</script>
@endsection
