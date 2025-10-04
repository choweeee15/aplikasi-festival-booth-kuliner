@extends('layouts.main')
@section('title', 'Ajukan Sewa Booth')

@section('content')
<div class="container-xxl py-5">
  <div class="container">
    <div class="row g-4">
      {{-- FORM --}}
      <div class="col-lg-7">
        <h2 class="mb-3">Ajukan Sewa Booth</h2>

        <div class="card shadow border-0">
          <div class="card-body">
            {{-- Alerts --}}
            @if ($errors->any())
              <div class="alert alert-danger">
                <ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
              </div>
            @endif
            @if (session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
            @if (session('error'))   <div class="alert alert-danger">{{ session('error') }}</div> @endif

            <form method="POST" action="{{ route('user.booth.sewa.store', $booth->id) }}" id="formSewa" novalidate>
              @csrf

              {{-- Nama (opsional, untuk keperluan internal/nota) --}}
              <div class="mb-3">
                <label class="form-label">Nama (opsional)</label>
                <input type="text" name="nama" class="form-control" value="{{ old('nama') }}" placeholder="Nama penanggung jawab">
              </div>

              {{-- Email (diambil dari akun login, readonly agar konsisten) --}}
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label class="form-label">Email Akun</label>
                  <input type="email" class="form-control" value="{{ auth()->user()->email ?? '-' }}" readonly>
                  <small class="text-muted">Email ini otomatis dari akun login & dipakai untuk verifikasi.</small>
                </div>

                <div class="col-md-6 mb-3">
                  <label class="form-label">No HP (opsional)</label>
                  <input type="text" name="no_hp" class="form-control" value="{{ old('no_hp') }}" placeholder="08xx xxxx xxxx">
                </div>
              </div>

              {{-- Periode sewa --}}
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label class="form-label">Tanggal Mulai <span class="text-danger">*</span></label>
                  <input type="date" name="tanggal_mulai" class="form-control" value="{{ old('tanggal_mulai') }}" required>
                </div>
                <div class="col-md-6 mb-3">
                  <label class="form-label">Tanggal Selesai <span class="text-danger">*</span></label>
                  <input type="date" name="tanggal_selesai" class="form-control" value="{{ old('tanggal_selesai') }}" required>
                </div>
              </div>

              {{-- Ringkasan harga (live) --}}
              <div class="alert alert-info d-flex flex-wrap justify-content-between align-items-center gap-2">
                <div>
                  <div><strong>Harga Sewa</strong>: Rp {{ number_format((int)($booth->harga_sewa ?? 0), 0, ',', '.') }} <span class="text-muted">/ hari</span></div>
                  <small class="text-muted">Total akhir tetap dihitung ulang di server saat disimpan.</small>
                </div>
                <div class="text-end">
                  <div class="small text-muted">Durasi:</div>
                  <div class="fw-bold" id="durasiHari">-</div>
                </div>
                <div class="text-end">
                  <div class="small text-muted">Estimasi Total:</div>
                  <div class="fw-bold" id="estimasiTotal">Rp 0</div>
                </div>
              </div>

              {{-- Catatan (opsional) --}}
              <div class="mb-3">
                <label class="form-label">Catatan (opsional)</label>
                <textarea name="catatan" class="form-control" rows="3" placeholder="Permintaan khusus, kebutuhan listrik, dekorasi, dsb.">{{ old('catatan') }}</textarea>
              </div>

              <button id="btnSubmit" class="btn btn-danger w-100">
                <i class="fas fa-paper-plane me-1"></i> Kirim Pengajuan
              </button>
            </form>
          </div>
        </div>
      </div>

      {{-- SIDEBAR BOOTH (sticky) --}}
      <div class="col-lg-5">
        <div class="card shadow border-0 position-sticky" style="top: 90px;">
          {{-- Gambar tetap --}}
          <img src="/img/booth-placeholder.jpg" class="card-img-top" alt="Booth">
          <div class="card-body">
            <h5 class="mb-1">{{ $booth->nama_booth }}</h5>
            <p class="text-muted small mb-2">{{ \Illuminate\Support\Str::limit($booth->deskripsi, 160) }}</p>
            <div class="d-flex align-items-center justify-content-between">
              <span class="fw-bold text-danger">Rp {{ number_format((int)($booth->harga_sewa ?? 0), 0, ',', '.') }}/hari</span>
              <span class="badge bg-{{ ($booth->status ?? 'tersedia') === 'tersedia' ? 'success' : 'secondary' }}">
                {{ ucfirst($booth->status ?? 'tersedia') }}
              </span>
            </div>
          </div>
          <div class="card-footer bg-white border-0">
            {{-- Tombol kembali ke katalog tetap ada --}}
            <a href="{{ route('user.booth.katalog') }}" class="btn btn-outline-danger w-100">
              <i class="fas fa-arrow-left me-1"></i> Kembali ke Katalog
            </a>
          </div>
        </div>
      </div>
    </div>

    @if (session('error')) <div class="alert alert-danger mt-3">{{ session('error') }}</div> @endif
  </div>
</div>
@endsection

@push('scripts')
<script>
  // Harga per hari dari server
  const HARGA_PER_HARI = {{ (int)($booth->harga_sewa ?? 0) }};

  const mulaiEl   = document.querySelector('[name="tanggal_mulai"]');
  const selesaiEl = document.querySelector('[name="tanggal_selesai"]');
  const durasiEl  = document.getElementById('durasiHari');
  const totalEl   = document.getElementById('estimasiTotal');
  const btnSubmit = document.getElementById('btnSubmit');

  function toRupiah(n){ return 'Rp ' + (n||0).toLocaleString('id-ID'); }

  function parseDate(v){
    if(!v) return null;
    const d = new Date(v+'T00:00:00');
    return isNaN(d) ? null : d;
  }

  function calcDays(a,b){
    const MS = 24*60*60*1000;
    return Math.max(1, Math.round((b - a)/MS) + 1); // inklusif, min 1
  }

  function refreshSummary(){
    const m = parseDate(mulaiEl.value);
    const s = parseDate(selesaiEl.value);

    // reset default
    let valid = true, durasi = '-', total = 0;

    if(!m || !s){
      valid = false;
    } else if(s < m){
      valid = false;
    } else {
      const d = calcDays(m, s);
      durasi = d + ' hari';
      total = d * HARGA_PER_HARI;
    }

    durasiEl.textContent = durasi;
    totalEl.textContent = toRupiah(total);

    // Disable submit kalau tanggal invalid
    btnSubmit.disabled = !valid;
  }

  mulaiEl?.addEventListener('change', refreshSummary);
  selesaiEl?.addEventListener('change', refreshSummary);
  refreshSummary();
</script>
@endpush
