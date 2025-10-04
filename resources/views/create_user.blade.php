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
            <i class="fas fa-user-plus me-2 text-success"></i> Tambah Pengguna
        </h3>

        <form action="{{ route('user.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name">Nama</label>
                <input type="text" name="name" id="name" class="form-control shadow-sm" placeholder="Masukkan nama lengkap" required>
            </div>
            <div class="mb-3">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="form-control shadow-sm" placeholder="contoh@email.com" required>
            </div>
            <div class="mb-3">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" class="form-control shadow-sm" placeholder="Minimal 6 karakter" required>
            </div>
            <div class="mb-3">
                <label for="role">Role</label>
                <select name="role" id="role" class="form-control shadow-sm" required>
                    <option value="">-- Pilih Role --</option>
                    <option value="admin">Admin</option>
                    <option value="pengguna">Pengguna</option>
                    <option value="petugas">Petugas</option>
                </select>
            </div>
            <div class="d-flex justify-content-start gap-2 mt-4">
                <button type="submit" class="btn btn-success shadow-sm">
                    <i class="fas fa-save me-1"></i> Simpan
                </button>
                <a href="{{ route('user.index') }}" class="btn btn-secondary shadow-sm">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
