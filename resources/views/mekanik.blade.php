@extends('layouts.app')

@section('title', 'Daftar Mekanik')

@section('dashboard-css')
<style>
    /* Container styling mirip dashboard */
    .mekanik-container {
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

    /* Tabel styling */
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

    .table td,
    .table th {
        vertical-align: middle;
        font-family: 'Montserrat', sans-serif;
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

    /* Button styles */
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
    }

    .btn-danger {
        background-color: #dc3545;
        border: none;
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
<div class="mekanik-container">
    <div class="d-flex justify-content-between align-items-center mb-4" data-aos="fade-down" data-aos-duration="700">
        <h2 class="title-heading"><i class="fas fa-wrench me-2"></i> Daftar Mekanik</h2>
        <button class="btn btn-primary btn-sm shadow-sm" data-bs-toggle="modal" data-bs-target="#createModal">
            <i class="fas fa-plus me-1"></i> Tambah Mekanik
        </button>
    </div>

    <div class="card card-dark" data-aos="fade-up" data-aos-duration="700" data-aos-delay="200">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-dark table-striped align-middle text-center mb-0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Telepon</th>
                            <th>Status</th>
                            <th>Terdaftar Sejak</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($mekanik as $index => $m)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $m->nama }}</td>
                            <td>{{ $m->telepon }}</td>
                            <td>
                                <span class="badge bg-{{ $m->status == 'aktif' ? 'success' : 'secondary' }}">
                                    {{ ucfirst($m->status) }}
                                </span>
                            </td>
                            <td>{{ $m->created_at->format('d M Y') }}</td>
                            <td>
                                <button class="btn btn-sm btn-warning me-1 shadow-sm" data-bs-toggle="modal" data-bs-target="#editModal{{ $m->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('mekanik.destroy', $m->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger shadow-sm"><i class="fas fa-trash-alt"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">Belum ada data mekanik.</td>
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
        <form method="POST" action="{{ route('mekanik.store') }}">
            @csrf
            <div class="modal-content bg-dark">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Mekanik</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Nama</label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Telepon</label>
                        <input type="text" name="telepon" class="form-control">
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
                    <button type="submit" class="btn btn-success">Simpan</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit untuk Setiap Mekanik -->
@foreach ($mekanik as $m)
<div class="modal fade" id="editModal{{ $m->id }}" tabindex="-1" aria-hidden="true" data-aos="fade-up" data-aos-duration="700">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('mekanik.update', $m->id) }}">
            @csrf
            @method('PUT')
            <div class="modal-content bg-dark">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Mekanik</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Nama</label>
                        <input type="text" name="nama" class="form-control" value="{{ $m->nama }}" required>
                    </div>
                    <div class="mb-3">
                        <label>Telepon</label>
                        <input type="text" name="telepon" class="form-control" value="{{ $m->telepon }}">
                    </div>
                    <div class="mb-3">
                        <label>Status</label>
                        <select name="status" class="form-select">
                            <option value="aktif" {{ $m->status == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ $m->status == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
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
