@extends('layouts.app')

@section('title', 'Service List')

@section('dashboard-css')
<style>
    .title-heading {
        font-size: 2.25rem;
        font-weight: 700;
        color: #f8f8f8;
        font-family: 'Montserrat', sans-serif;
    }

    .dashboard-container {
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

    .card-dark {
        background-color: #5a0000;
        border-radius: 12px;
        border: none;
        color: white;
        box-shadow: 0 8px 20px rgb(255 0 0 / 0.25);
    }

    .btn-primary {
        background: linear-gradient(45deg, #b30000, #7b0000);
        color: #fff;
        border: none;
        font-weight: 600;
        box-shadow: 0 4px 10px rgba(179,0,0,0.6);
        transition: background 0.3s ease;
    }

    .btn-primary:hover {
        background: linear-gradient(45deg, #d10000, #a30000);
        box-shadow: 0 6px 15px rgba(209,0,0,0.75);
    }

    .btn-warning {
        background-color: #ffc107;
        color: #212529;
        border: none;
        font-weight: 600;
        box-shadow: 0 4px 8px rgba(255,193,7,0.6);
        transition: background-color 0.3s ease;
    }

    .btn-danger {
        background-color: #dc3545;
        border: none;
        font-weight: 600;
        box-shadow: 0 4px 8px rgba(220,53,69,0.6);
        transition: background-color 0.3s ease;
    }

    .table {
        background: #fff;
        color: #000;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 8px 20px rgb(255 0 0 / 0.2);
    }

    .table thead th {
        background: linear-gradient(45deg, #7b0000, #b30000);
        color: #fff;
        font-weight: 700;
        vertical-align: middle;
    }

    .table tbody tr:hover {
        background-color: rgba(255, 230, 230, 0.6);
    }

    .table td,
    .table th {
        vertical-align: middle;
        font-family: 'Montserrat', sans-serif;
    }

    /* Modal styling */
    .modal-content.bg-dark {
        background: linear-gradient(135deg, #4e0000, #800000);
        color: white;
        border-radius: 12px;
    }

    .modal-header .btn-close {
        filter: brightness(0) invert(1);
    }

    .modal-footer button {
        font-weight: 600;
    }
</style>
@endsection

@section('content')
<div class="dashboard-container">
    <div class="d-flex justify-content-between align-items-center mb-4" data-aos="fade-down" data-aos-duration="700" data-aos-delay="200">
        <h2 class="title-heading"><i class="fas fa-gears me-2"></i> Daftar Layanan</h2>
        <button class="btn btn-primary btn-sm shadow-sm" data-bs-toggle="modal" data-bs-target="#createModal">
            <i class="fas fa-plus me-1"></i> Tambah Layanan
        </button>
    </div>

    <div class="card card-dark" data-aos="fade-up" data-aos-duration="700" data-aos-delay="300">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped text-center align-middle mb-0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Harga</th>
                            <th>Estimasi</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($layanans as $index => $layanan)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $layanan->nama_layanan }}</td>
                            <td>Rp{{ number_format($layanan->harga, 0, ',', '.') }}</td>
                            <td>{{ $layanan->estimasi }} menit</td>
                            <td>
                                <span class="badge bg-{{ $layanan->status == 'aktif' ? 'success' : 'secondary' }}">
                                    {{ ucfirst($layanan->status) }}
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-warning me-1" data-bs-toggle="modal" data-bs-target="#editModal{{ $layanan->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('layanan.destroy', $layanan->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus layanan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">Belum ada layanan tersedia.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('layanan.store') }}">
            @csrf
            <div class="modal-content bg-dark">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Layanan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Layanan</label>
                        <input type="text" name="nama_layanan" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Harga (Rp)</label>
                        <input type="number" name="harga" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Estimasi (menit)</label>
                        <input type="number" name="estimasi" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="aktif" selected>Aktif</option>
                            <option value="nonaktif">Nonaktif</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit -->
@foreach ($layanans as $layanan)
<div class="modal fade" id="editModal{{ $layanan->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('layanan.update', $layanan->id) }}">
            @csrf
            @method('PUT')
            <div class="modal-content bg-dark">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Layanan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Layanan</label>
                        <input type="text" name="nama_layanan" class="form-control" value="{{ $layanan->nama_layanan }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Harga (Rp)</label>
                        <input type="number" name="harga" class="form-control" value="{{ $layanan->harga }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Estimasi (menit)</label>
                        <input type="number" name="estimasi" class="form-control" value="{{ $layanan->estimasi }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="aktif" {{ $layanan->status == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ $layanan->status == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
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
