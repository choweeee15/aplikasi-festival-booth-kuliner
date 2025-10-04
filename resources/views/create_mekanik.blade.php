@extends('layouts.app')

@section('content')
<style>
    body {
        background: linear-gradient(to right, #f3f4f6, #e2e8f0);
    }

    .card-custom {
        background: linear-gradient(to bottom right, #ffffff, #f9f9f9);
        border-radius: 12px;
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.08);
        border: none;
    }

    .form-control:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
    }

    label {
        font-weight: 600;
        color: #333;
    }

    .btn-success {
        background: linear-gradient(45deg, #28a745, #218838);
        border: none;
    }

    .btn-secondary {
        background-color: #6c757d;
        border: none;
    }
</style>

<div class="card card-custom">
    <div class="card-body">
        <h3 class="mb-4 text-dark d-flex align-items-center">
            <i class="fas fa-tools me-2 text-primary"></i> Tambah Mekanik
        </h3>

        <form action="{{ route('mekanik.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="nama">Nama</label>
                <input type="text" name="nama" id="nama" class="form-control shadow-sm" placeholder="Masukkan nama mekanik" required>
            </div>
            <div class="mb-3">
                <label for="telepon">Telepon</label>
                <input type="text" name="telepon" id="telepon" class="form-control shadow-sm" placeholder="08123456789" required>
            </div>
            <div class="mb-3">
                <label for="status">Status</label>
                <select name="status" id="status" class="form-control shadow-sm" required>
                    <option value="Aktif">Aktif</option>
                    <option value="Tidak Aktif">Tidak Aktif</option>
                </select>
            </div>
            <div class="d-flex justify-content-start gap-2 mt-4">
                <button type="submit" class="btn btn-success shadow-sm">
                    <i class="fas fa-save me-1"></i> Simpan
                </button>
                <a href="{{ route('mekanik.index') }}" class="btn btn-secondary shadow-sm">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
