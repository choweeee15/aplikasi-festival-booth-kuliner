@extends('layouts.main')
@section('title', 'Akun Pengunjung')

@section('content')
<div class="container-xxl py-5">
  <div class="container">

    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h2 class="mb-0">Akun Pengunjung</h2>
        <small class="text-muted">Kelola data profil untuk pembelian tiket & keperluan festival.</small>
      </div>
      <a href="{{ route('pembelian-tiket.index', ['email' => $email]) }}" class="btn btn-danger">
        <i class="fas fa-ticket-alt me-1"></i> Lihat Pembelian
      </a>
    </div>

    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if($errors->any())
      <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
    @endif

    <form method="GET" action="{{ route('pengunjungs.index') }}" class="row g-2 mb-4">
      <div class="col-md-6">
        <input type="email" name="email" class="form-control" placeholder="Cari email pengunjung" value="{{ $email }}">
      </div>
      <div class="col-md-3 d-grid">
        <button class="btn btn-outline-danger"><i class="fas fa-search me-1"></i> Cek</button>
      </div>
    </form>

    <div class="card shadow border-0">
      <div class="card-body">
        <h5 class="mb-3">Data Profil</h5>
        <form method="POST" action="{{ route('pengunjungs.store') }}">
          @csrf
          <div class="row">
            <div class="col-md-6 mb-3">
              <label class="form-label">Nama</label>
              <input type="text" name="nama" class="form-control" value="{{ old('nama', $pengunjung->nama ?? '') }}" required>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">Email</label>
              <input type="email" name="email" class="form-control" value="{{ old('email', $pengunjung->email ?? $email ?? '') }}" required>
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label">No HP</label>
              <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp', $pengunjung->no_hp ?? '') }}">
            </div>
          </div>
          <button class="btn btn-danger"><i class="fas fa-save me-1"></i> Simpan</button>
        </form>
      </div>
    </div>

  </div>
</div>
@endsection
