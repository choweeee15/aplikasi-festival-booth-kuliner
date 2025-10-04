@extends('layouts.main')
@extends('layouts.navbar')

@section('title', 'Riwayat Booking')

@section('content')
<div class="container py-5">
    <div class="d-flex align-items-center mb-3">
        <h2 class="me-4 mb-0">Riwayat Booking Anda</h2>
        <div>
            <strong>Transfer ke:</strong> Bank BCA 1234-5678-9012 a.n. Bengkel Chowe
        </div>
    </div>

    @if($bookings->isEmpty())
        <div class="alert alert-info">Belum ada riwayat booking.</div>
    @else
        <div class="table-responsive">
            <table class="table table-bordered table-hover bg-white">
                <thead>
                    <tr>
                            <th>Tanggal</th>
                            <th>Layanan</th>
                            <th>Nama Kendaraan</th>
                            <th>Model</th>
                            <th>Tagihan & Rincian Biaya</th> <!-- Pindah ke sini -->
                            <th>Bukti Pembayaran</th>
                            <th>Status</th> <!-- Jadi kolom paling kanan -->
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bookings as $item)
                            <tr>
                                <td>{{ $item->created_at->format('d M Y H:i') }}</td>
                                <td>{{ $item->layanan->nama_layanan ?? '-' }}</td>
                                <td>{{ $item->nama_kendaraan ?? '-' }}</td>
                                <td>{{ $item->model_kendaraan ?? '-' }}</td>
                
                                {{-- Tagihan & Rincian Biaya --}}
                                <td>
                                    @if($item->pembayaran)
                                        @if($item->pembayaran->jumlah_tagihan && $item->pembayaran->rincian_biaya)
                                            <strong>Rp{{ number_format($item->pembayaran->jumlah_tagihan, 0, ',', '.') }}</strong><br>
                                            <small style="white-space: pre-wrap;">{{ $item->pembayaran->rincian_biaya }}</small>
                                        @else
                                            <span class="text-muted">Menunggu rincian biaya dan tagihan</span>
                                        @endif
                                    @else
                                        <span class="text-muted">Belum ada tagihan</span>
                                    @endif
                                </td>

                                {{-- Bukti Pembayaran --}}
                                <td>
                                    @if($item->status === 'dikonfirmasi' && (!$item->pembayaran || !$item->pembayaran->bukti_pembayaran))
                                        <form method="POST" action="{{ route('pembayaran.upload', $item->id) }}" enctype="multipart/form-data">
                                            @csrf
                                            <input type="file" name="bukti_pembayaran" required class="form-control mb-2" accept="image/*,application/pdf">
                                            <button type="submit" class="btn btn-sm btn-success">Upload</button>
                                        </form>
                                    @elseif($item->pembayaran && $item->pembayaran->bukti_pembayaran)
                                        <a href="{{ asset('storage/' . $item->pembayaran->bukti_pembayaran) }}" target="_blank">Lihat</a>
                                        <small class="d-block text-muted">{{ ucfirst($item->pembayaran->status) }}</small>
                                    @else
                                        <span class="text-muted">Belum ada</span>
                                    @endif
                                </td>
                
                                
                
                                {{-- Status --}}
                                <td>
                                    <span class="badge bg-{{ match($item->status) {
                                        'menunggu' => 'secondary',
                                        'dikonfirmasi' => 'primary',
                                        'diproses' => 'warning',
                                        'selesai' => 'success',
                                        'ditolak' => 'danger',
                                        default => 'light'
                                    } }}">
                                        {{ ucfirst($item->status) }}
                                    </span>
                
                                    @if($item->status === 'menunggu')
                                        <div class="small text-muted mt-1">Menunggu konfirmasi admin sebelum upload bukti pembayaran.</div>
                
                                    @elseif($item->status === 'dikonfirmasi' && (!$item->pembayaran || !$item->pembayaran->bukti_pembayaran))
                                        <div class="small text-warning mt-1">Silakan upload bukti pembayaran.</div>
                
                                    @elseif($item->pembayaran && $item->pembayaran->status === 'menunggu')
                                        <div class="small text-info mt-1">Bukti pembayaran dikirim, menunggu verifikasi.</div>
                
                                    @elseif($item->pembayaran && $item->pembayaran->status === 'diverifikasi')
                                        <div class="small text-success mt-1">Pembayaran telah diverifikasi.</div>
                
                                    @elseif($item->pembayaran && $item->pembayaran->status === 'ditolak')
                                        <div class="small text-danger mt-1">Bukti pembayaran ditolak.</div>
                                    @endif
                
                                    @if($item->pembayaran && $item->pembayaran->catatan && $item->pembayaran->status === 'ditolak')
                                        <div class="small text-danger mt-1">Alasan Ditolak: <em>{{ $item->pembayaran->catatan }}</em></div>
                                    @endif
                                </td>
                        </tr>
                    @endforeach
                    
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
