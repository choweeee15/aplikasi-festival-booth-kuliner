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

    label {
        font-weight: 600;
        color: #333;
    }

    .form-control:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
    }

    .btn-primary {
        background: linear-gradient(45deg, #007bff, #0056b3);
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
            <i class="fas fa-user-edit me-2 text-primary"></i> Edit Pengguna
        </h3>

        <form action="{{ route('user.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="name">Nama</label>
                <input type="text" name="name" id="name" class="form-control shadow-sm" value="{{ $user->name }}" required>
            </div>
            <div class="mb-3">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="form-control shadow-sm" value="{{ $user->email }}" required>
            </div>
            <div class="mb-3">
                <label for="role">Role</label>
                <select name="role" id="role" class="form-control shadow-sm" required>
                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="pengguna" {{ $user->role == 'pengguna' ? 'selected' : '' }}>Pengguna</option>
                    <option value="petugas" {{ $user->role == 'petugas' ? 'selected' : '' }}>Petugas</option>
                </select>
            </div>
            <div class="d-flex justify-content-start gap-2 mt-4">
                <button type="submit" class="btn btn-primary shadow-sm">
                    <i class="fas fa-save me-1"></i> Update
                </button>
                <a href="{{ route('user.index') }}" class="btn btn-secondary shadow-sm">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
