@extends('layouts.app')

@section('title', 'Service Requests')

@section('dashboard-css')
<style>
    .title-heading {
        font-size: 2rem;
        font-weight: 700;
        color: #f8f8f8;
    }

    .card-dark {
        background-color: #5a0000;
        border: none;
        border-radius: 12px;
    }

    .modal-content {
        background-color: #400000;
        border-radius: 10px;
        color: white;
    }

    .modal-header {
        border-bottom: 1px solid #911;
    }

    .modal-footer {
        border-top: 1px solid #911;
    }

    .dropdown-menu {
        background-color: #2e0000;
        color: white;
    }

    .dropdown-item {
        color: white;
    }

    .dropdown-item:hover {
        background-color: #770000;
    }

    .badge {
        font-size: 0.85rem;
    }
</style>

<style>
    table thead {
    background-color: #000;
    color: #fff;
}

table tbody {
    background-color: #fff;
    color: #000;
}

table tbody tr:hover {
    background-color: #f0f0f0;
}
.btn-maroon-stroke {
  background-color: transparent;
  color: #a52a2a;  /* warna maroon muda untuk teks */
  border: 2px solid #a52a2a; /* stroke warna maroon muda */
  transition: background-color 0.3s, color 0.3s;
}

.btn-maroon-stroke:hover {
  background-color: #a52a2a;  /* isi warna saat hover */
  color: white;              /* teks berubah putih saat hover */
  border-color: #a52a2a;
}


</style>

@endsection

@section('content')
<div class="dashboard-container">
    @if(session('success'))
        <div class="alert alert-success text-center">
            {{ session('success') }}
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="title-heading">📋 Daftar Permintaan Layanan</h2>
        <button class="btn btn-gradient-red rounded-pill" data-bs-toggle="modal" data-bs-target="#createModal">
            + Tambah Permintaan
        </button>
    </div>

    <div class="card card-dark" data-aos="fade-up" data-aos-duration="700" data-aos-delay="200">
        <div class="card-body p-4" style="overflow-x: auto;">
            <table class="table table-hover table-light table-striped align-middle text-center mb-0">
                <thead style="background-color: #000; color: #fff;">
                    <tr>
                        <th style="padding: 12px 15px;">#</th>
                        <th style="padding: 12px 15px;">Tanggal</th>
                        <th style="padding: 12px 15px;">Nama Klien</th>
                        <th style="padding: 12px 15px;">Layanan</th>
                        <th style="padding: 12px 15px;">Catatan Pelanggan</th>
                        <th style="padding: 12px 15px;">Status</th>
                        <th style="padding: 12px 15px;">Bukti</th>
                        <th style="padding: 12px 15px;">Tagihan & Rincian Biaya</th>
                        <th style="padding: 12px 15px;">Aksi</th>
                    </tr>
                </thead>
                <tbody style="background-color: #fff; color: #000;">
                    @forelse($requests as $index => $req)
                        <tr>
                            <td style="padding: 10px 15px; white-space: nowrap;">{{ $index + 1 }}</td>
                            <td style="padding: 10px 15px; white-space: nowrap;">{{ $req->created_at->format('d M Y H:i') }}</td>
                            <td style="padding: 10px 15px; white-space: nowrap;">{{ $req->nama_pemilik }}</td>
                            <td style="padding: 10px 15px; white-space: nowrap;">{{ $req->layanan->nama_layanan ?? '-' }}</td>
                            <td style="padding: 10px 15px; white-space: nowrap;">{{ $req->catatan_admin }}</td>
                            <td style="padding: 10px 15px; white-space: nowrap;">
                                @php
                                    $color = match($req->status) {
                                        'menunggu' => 'secondary',
                                        'dikonfirmasi' => 'primary',
                                        'diproses' => 'warning',
                                        'selesai' => 'success',
                                        'ditolak' => 'danger',
                                        default => 'light'
                                    };
                                @endphp
                                <span class="badge bg-{{ $color }} px-3 py-2 rounded-pill">
                                    {{ ucfirst($req->status) }}
                                </span>
                            </td>
                            <td style="padding: 10px 15px; white-space: nowrap;">
                                @if($req->status === 'menunggu')
                                    <span class="text-muted">Menunggu konfirmasi admin</span>
                                @elseif($req->status === 'dikonfirmasi' && !$req->pembayaran)
                                    <span class="text-warning">Menunggu bukti pembayaran</span>
                                @elseif($req->pembayaran && $req->pembayaran->bukti_pembayaran)
                                <a href="{{ asset('storage/' . $req->pembayaran->bukti_pembayaran) }}" target="_blank" class="btn btn-sm btn-maroon-stroke mb-1">
                                    Lihat Bukti
                                </a>                                
                                    <br>
                                    <small class="text-black">Status: <strong>{{ ucfirst($req->pembayaran->status) }}</strong></small>
    
                                    @if($req->pembayaran->status === 'menunggu')
                                        <form method="POST" action="{{ route('pembayaran.verifikasi', $req->pembayaran->id) }}" class="mt-2 d-inline">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" name="aksi" value="verifikasi" class="btn btn-sm btn-success">Verifikasi</button>
                                        </form>
                                    @elseif($req->pembayaran->status === 'ditolak')
                                        <div class="mt-2 small text-danger">
                                            <strong>Ditolak:</strong> {{ $req->pembayaran->catatan ?? '-' }}
                                        </div>
                                        <div class="small text-warning">
                                            Silakan unggah ulang bukti pembayaran.
                                        </div>
                                    @endif
                                @else
                                    <span class="text-muted">Belum ada</span>
                                @endif
                            </td>
    
                            <!-- Tagihan & Rincian Biaya -->
                            <td style="padding: 10px 15px; white-space: normal; max-width: 300px;">
                                @if($req->pembayaran)
                                    <strong>Rp{{ number_format($req->pembayaran->jumlah_tagihan ?? 0, 0, ',', '.') }}</strong><br>
                                    <small style="white-space: pre-wrap; color: #333;">{{ $req->pembayaran->rincian_biaya ?? '-' }}</small>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
    
                            <td style="padding: 10px 15px; white-space: nowrap;">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light dropdown-toggle" data-bs-toggle="dropdown">Aksi</button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#detailModal{{ $req->id }}">
                                                Lihat Detail
                                            </button>
                                        </li>
                                        <li>
                                            <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#statusModal{{ $req->id }}">
                                                Ubah Status
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">Belum ada permintaan layanan.</td>
                        </tr>
                    @endforelse
{{-- Modal Alasan Penolakan (Pastikan berada di luar table) --}}
@foreach($requests as $req)
@if($req->pembayaran && $req->pembayaran->status === 'menunggu')
<div class="modal fade" id="tolakModal{{ $req->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('pembayaran.verifikasi', $req->pembayaran->id) }}">
            @csrf
            @method('PUT')
            <input type="hidden" name="aksi" value="tolak">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header">
                    <h5 class="modal-title">Alasan Penolakan</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Masukkan alasan penolakan:</label>
                        <textarea name="catatan_penolakan" class="form-control" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger">Tolak</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endif
@endforeach

                </tbody>
            </table>
        </div>
    </div>
</div>


{{-- Modal Tambah --}}
<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('service.requests.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Permintaan Layanan</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Pemilik</label>
                        <input type="text" class="form-control" name="nama_pemilik" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Layanan</label>
                        <select name="layanan_id" class="form-select" required>
                            <option value="" disabled selected>Pilih Layanan...</option>
                            @foreach($layanans as $layanan)
                                <option value="{{ $layanan->id }}">{{ $layanan->nama_layanan }} - Rp{{ number_format($layanan->harga, 0, ',', '.') }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tipe Kendaraan</label>
                        <input type="text" class="form-control" name="tipe_kendaraan">
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success" type="submit">Kirim</button>
                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Batal</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Semua Modal Edit & Detail Diletakkan di Bawah --}}
{{-- Semua Modal Edit & Detail Diletakkan di Bawah --}}
@foreach($requests as $req)
    {{-- Detail Modal --}}
    <div class="modal fade" id="detailModal{{ $req->id }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Permintaan</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Nama Pemilik:</strong> {{ $req->nama_pemilik }}</p>
                    <p><strong>Layanan:</strong> {{ $req->layanan->nama_layanan ?? '-' }}</p>
                    <p><strong>Tipe Kendaraan:</strong> {{ $req->tipe_kendaraan ?? '-' }}</p>
                    <p><strong>Status:</strong> {{ ucfirst($req->status) }}</p>
                    <p><strong>Tanggal:</strong> {{ $req->created_at->format('d M Y H:i') }}</p>
                    @if($req->pembayaran && $req->pembayaran->bukti_pembayaran)
    <a href="{{ asset('storage/' . $req->pembayaran->bukti_pembayaran) }}" target="_blank" class="btn btn-sm btn-outline-light mb-1">
        Lihat Bukti
    </a>
    <br>
    <small class="text-white">Status: <strong>{{ ucfirst($req->pembayaran->status) }}</strong></small>

    @if($req->pembayaran->status === 'menunggu')
        <form method="POST" action="{{ route('pembayaran.verifikasi', $req->pembayaran->id) }}" class="mt-2 d-inline">
            @csrf
            @method('PUT')
            <button type="submit" name="aksi" value="verifikasi" class="btn btn-sm btn-success">Verifikasi</button>
        </form>

        <!-- Tombol tolak dengan modal -->
        <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#tolakModal{{ $req->id }}">Tolak</button>

        
    @elseif($req->pembayaran->status === 'ditolak')
        <div class="text-danger mt-2 small">
            Alasan Ditolak: <em>{{ $req->pembayaran->catatan }}</em>
        </div>
        <div class="text-warning small">
            Silakan unggah ulang bukti pembayaran.
        </div>
    @endif
@else
    <span class="text-muted">Belum ada</span>
@endif

                </div>
            </div>
        </div>
    </div>

    {{-- Status Modal --}}
    <div class="modal fade" id="statusModal{{ $req->id }}" tabindex="-1">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('service.requests.updateStatus', $req->id) }}">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Ubah Status Permintaan</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" class="form-select" onchange="toggleInputFields(this, {{ $req->id }})">
                                <option value="menunggu" {{ $req->status === 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                                <option value="dikonfirmasi" {{ $req->status === 'dikonfirmasi' ? 'selected' : '' }}>Dikonfirmasi</option>
                                <option value="diproses" {{ $req->status === 'diproses' ? 'selected' : '' }}>Diproses</option>
                                <option value="selesai" {{ $req->status === 'selesai' ? 'selected' : '' }}>Selesai</option>
                                <option value="ditolak" {{ $req->status === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                            </select>
                        </div>
                        
                        <div id="tagihan-{{ $req->id }}" style="display: none;">
                            <label>Jumlah Tagihan (Rp):</label>
                            <input type="number" name="jumlah_tagihan" class="form-control" value="{{ $req->pembayaran->jumlah_tagihan ?? '' }}">
                        </div>
                        
                        <div id="rincian-{{ $req->id }}" style="display: none; margin-top: 10px;">
                            <label>Rincian Biaya & Kerusakan:</label>
                            <textarea name="rincian_biaya" class="form-control" rows="4" placeholder="Jelaskan rincian biaya dan kerusakan...">{{ $req->pembayaran->rincian_biaya ?? '' }}</textarea>
                        </div>
                        
                        <div id="catatan-{{ $req->id }}" style="display: none; margin-top: 10px;">
                            <label>Alasan Penolakan:</label>
                            <textarea name="catatan_penolakan" class="form-control">{{ $req->pembayaran->catatan ?? '' }}</textarea>
                        </div>
                        
                        
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Simpan</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endforeach

@endsection

<script>
    function toggleInputFields(select, id) {
    let tagihan = document.getElementById('tagihan-' + id);
    let rincian = document.getElementById('rincian-' + id);
    let catatan = document.getElementById('catatan-' + id);

    if (!tagihan || !catatan || !rincian) return;

    if (select.value === 'dikonfirmasi') {
        tagihan.style.display = 'block';
        rincian.style.display = 'block';
        catatan.style.display = 'none';
    } else if (select.value === 'ditolak') {
        tagihan.style.display = 'none';
        rincian.style.display = 'none';
        catatan.style.display = 'block';
    } else {
        tagihan.style.display = 'none';
        rincian.style.display = 'none';
        catatan.style.display = 'none';
    }
}


    // Jalankan toggle otomatis saat modal dibuka
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('form[action*="updateStatus"]').forEach(form => {
            const select = form.querySelector('select[name="status"]');
            const action = form.getAttribute('action');
            const match = action.match(/\/(\d+)$/);

            if (select && match) {
                const id = match[1];
                toggleInputFields(select, id);

                // Pastikan onchange tetap bekerja
                select.addEventListener('change', function () {
                    toggleInputFields(this, id);
                });
            }
        });
    });
</script>

    


