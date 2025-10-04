@extends('layouts.main')
@extends('layouts.navbar')

@section('title', 'Riwayat Pembayaran')

@section('content')
<div class="container py-5">
    <h2 class="mb-4">Riwayat Pembayaran Anda</h2>

    @if($payments->isEmpty())
        <div class="alert alert-info">Belum ada riwayat pembayaran.</div>
    @else
        <div class="table-responsive">
            <table class="table table-bordered table-hover bg-white">
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

                            <td>
                                {{ $payment->catatan ?? '-' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Pagination -->
            <div>
                {{ $payments->links() }}
            </div>
        </div>
    @endif
</div>
@endsection
