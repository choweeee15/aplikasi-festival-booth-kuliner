@extends('layouts.app')

@section('title', 'Category List')

@section('dashboard-css')
<style>
    .title-heading {
        font-size: 2rem;
        font-weight: bold;
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
        color: white;
        box-shadow: 0 6px 15px rgba(255, 0, 0, 0.3);
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
    .table td, .table th {
        vertical-align: middle;
        font-family: 'Montserrat', sans-serif;
    }
    .badge-success {
        background-color: #198754;
    }
    .badge-secondary {
        background-color: #6c757d;
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
<div class="dashboard-container" data-aos="fade-in" data-aos-duration="700">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="title-heading"><i class="fas fa-layer-group me-2"></i> Daftar Kategori Kendaraan</h2>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createModal">
            <i class="fas fa-plus me-1"></i> Tambah Kategori
        </button>
    </div>

    <div class="card card-dark" data-aos="fade-up" data-aos-duration="700" data-aos-delay="100">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 text-center">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama Kategori</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kategoriList as $kategori)
                        <tr>
                            <td>{{ $kategori->id }}</td>
                            <td>{{ $kategori->nama_kategori }}</td>
                            <td>
                                <span class="badge bg-{{ $kategori->status == 'aktif' ? 'success' : 'secondary' }}">
                                    {{ ucfirst($kategori->status) }}
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-warning me-1" data-bs-toggle="modal" data-bs-target="#editModal{{ $kategori->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('category.destroy', $kategori->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus kategori ini?')">
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
                            <td colspan="4" class="text-center text-muted">Belum ada data kategori.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true" data-aos="fade-up" data-aos-duration="700">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('category.store') }}">
            @csrf
            <div class="modal-content bg-dark">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Kategori</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Nama Kategori</label>
                        <input type="text" name="nama_kategori" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Status</label>
                        <select name="status" class="form-select">
                            <option value="aktif">Aktif</option>
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
@foreach($kategoriList as $kategori)
<div class="modal fade" id="editModal{{ $kategori->id }}" tabindex="-1" aria-hidden="true" data-aos="fade-up" data-aos-duration="700">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('category.update', $kategori->id) }}">
            @csrf @method('PUT')
            <div class="modal-content bg-dark">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Kategori</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Nama Kategori</label>
                        <input type="text" name="nama_kategori" class="form-control" value="{{ $kategori->nama_kategori }}" required>
                    </div>
                    <div class="mb-3">
                        <label>Status</label>
                        <select name="status" class="form-select">
                            <option value="aktif" {{ $kategori->status == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ $kategori->status == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
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
