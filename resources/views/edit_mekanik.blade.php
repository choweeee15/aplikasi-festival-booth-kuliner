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
            <i class="fas fa-edit me-2 text-primary"></i> Edit Mekanik
        </h3>

        <form action="{{ route('mekanik.update', $mekanik->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="nama">Nama</label>
                <input type="text" name="nama" id="nama" class="form-control shadow-sm" value="{{ $mekanik->nama }}" required>
            </div>
            <div class="mb-3">
                <label for="telepon">Telepon</label>
                <input type="text" name="telepon" id="telepon" class="form-control shadow-sm" value="{{ $mekanik->telepon }}" required>
            </div>
            <div class="mb-3">
                <label for="status">Status</label>
                <select name="status" id="status" class="form-control shadow-sm" required>
                    <option value="Aktif" {{ $mekanik->status == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="Tidak Aktif" {{ $mekanik->status == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
            </div>

            <div class="d-flex justify-content-start gap-2 mt-4">
                <button type="submit" class="btn btn-primary shadow-sm">
                    <i class="fas fa-save me-1"></i> Update
                </button>
                <a href="{{ route('mekanik.index') }}" class="btn btn-secondary shadow-sm">
                    <i class="fas fa-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
