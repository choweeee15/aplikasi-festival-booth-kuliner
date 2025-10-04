@extends('layouts.main')
@section('title', 'Katalog Booth')

@section('content')
<div class="container-xxl py-5">
  <div class="container">
    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
      <div>
        <h1 class="mb-1">Katalog Booth 🎪</h1>
        <p class="text-muted mb-0">Pilih booth yang tersedia, lalu ajukan sewa.</p>
      </div>
      <a href="{{ route('user.home') }}" class="btn btn-outline-danger">
        <i class="fas fa-home me-1"></i> Home
      </a>
    </div>

    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if(session('error'))   <div class="alert alert-danger">{{ session('error') }}</div>   @endif

    {{-- Filter + Sort --}}
    <div class="card shadow-sm border-0 mb-4">
      <div class="card-body">
        <div class="row g-3 align-items-end">
          <div class="col-md-5">
            <label class="form-label">Cari booth</label>
            <div class="input-group">
              <span class="input-group-text"><i class="fas fa-search"></i></span>
              <input id="q" type="text" class="form-control" placeholder="Ketik nama/deskripsi booth...">
            </div>
          </div>
          <div class="col-md-3">
            <label class="form-label">Status</label>
            <select id="status" class="form-select">
              <option value="">Semua</option>
              <option value="tersedia">Tersedia</option>
              <option value="tersewa">Tersewa</option>
            </select>
          </div>
          <div class="col-md-3">
            <label class="form-label">Urutkan</label>
            <select id="sort" class="form-select">
              <option value="default">Default</option>
              <option value="harga-asc">Harga Terendah</option>
              <option value="harga-desc">Harga Tertinggi</option>
              <option value="nama-asc">Nama A → Z</option>
              <option value="nama-desc">Nama Z → A</option>
            </select>
          </div>
          <div class="col-md-1 d-none d-md-block text-end">
            <small class="text-muted">Hasil:</small>
            <div id="resultCount" class="fw-bold">0</div>
          </div>
        </div>
      </div>
    </div>

    {{-- Grid Booth --}}
    <div id="grid" class="row g-4">
      @forelse($booths as $booth)
        @php
          $harga = (int)($booth->harga_sewa ?? 0);
          $status = $booth->status ?? 'tersedia';
          $deskripsi = \Illuminate\Support\Str::limit((string)($booth->deskripsi ?? ''), 120);
          $nama = (string)$booth->nama_booth;
          $img  = $booth->image_url; // <— ambil dari accessor
        @endphp
        <div class="col-md-6 col-lg-4 booth-card"
             data-name="{{ strtolower($nama) }}"
             data-desc="{{ strtolower($booth->deskripsi ?? '') }}"
             data-status="{{ strtolower($status) }}"
             data-price="{{ $harga }}">
          <div class="card h-100 shadow border-0 hover-lift">
            <div class="ratio ratio-16x9">
              <img src="{{ $img }}" class="card-img-top rounded-top" alt="{{ $nama }}" style="object-fit: cover;"
                   onerror="this.src='/img/booth-placeholder.jpg'">
            </div>
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-start">
                <h5 class="card-title mb-1">{{ $nama }}</h5>
                <span class="badge bg-{{ $status=='tersedia'?'success':'secondary' }}">{{ ucfirst($status) }}</span>
              </div>
              <p class="text-muted small mb-3">{{ $deskripsi }}</p>
              <div class="d-flex align-items-center justify-content-between">
                <div>
                  <div class="fw-bold text-danger">Rp {{ number_format($harga,0,',','.') }}</div>
                  <small class="text-muted">/ hari</small>
                </div>
                @if(\Schema::hasColumn('booths','created_at') && $booth->created_at)
                  <small class="text-muted"><i class="far fa-clock me-1"></i>{{ \Carbon\Carbon::parse($booth->created_at)->diffForHumans() }}</small>
                @endif
              </div>
            </div>
            <div class="card-footer bg-white border-0">
              @php $available = ($status === 'tersedia'); @endphp
              <a href="{{ $available ? route('user.booth.sewa.create', $booth->id) : 'javascript:void(0)' }}"
                 class="btn btn-danger w-100 {{ $available ? '' : 'disabled' }}"
                 @if(!$available) aria-disabled="true" tabindex="-1" @endif>
                <i class="fas fa-handshake me-1"></i> Sewa Sekarang
              </a>
            </div>
          </div>
        </div>
      @empty
        <div class="col-12 text-center text-muted">Belum ada booth tersedia.</div>
      @endforelse
    </div>

    {{-- Pagination (jika $booths adalah paginator) --}}
    @if (method_exists($booths, 'links'))
      <div class="mt-4">
        {{ $booths->links() }}
      </div>
    @endif
  </div>
</div>

{{-- Sedikit gaya hover --}}
<style>
  .hover-lift { transition: transform .15s ease, box-shadow .15s ease; }
  .hover-lift:hover { transform: translateY(-3px); box-shadow: 0 10px 24px rgba(0,0,0,.08) !important; }
</style>

{{-- Filter & Sort client-side --}}
<script>
  const qEl     = document.getElementById('q');
  const stEl    = document.getElementById('status');
  const sortEl  = document.getElementById('sort');
  const grid    = document.getElementById('grid');
  const cards   = Array.from(document.querySelectorAll('.booth-card'));
  const countEl = document.getElementById('resultCount');

  function matches(card, q, st) {
    const name = card.dataset.name || '';
    const desc = card.dataset.desc || '';
    const stat = card.dataset.status || '';
    const qok  = !q || name.includes(q) || desc.includes(q);
    const stok = !st || stat === st;
    return qok && stok;
  }

  function applySort(list, mode) {
    const getPrice = c => parseInt(c.dataset.price || '0');
    const getName  = c => (c.dataset.name || '').toString();
    switch (mode) {
      case 'harga-asc':  list.sort((a,b)=> getPrice(a)-getPrice(b)); break;
      case 'harga-desc': list.sort((a,b)=> getPrice(b)-getPrice(a)); break;
      case 'nama-asc':   list.sort((a,b)=> getName(a).localeCompare(getName(b))); break;
      case 'nama-desc':  list.sort((a,b)=> getName(b).localeCompare(getName(a))); break;
      default: break;
    }
  }

  function render(list){
    cards.forEach(c => c.style.display = 'none');
    list.forEach(c => c.style.display = '');
    countEl.textContent = list.length;
  }

  function refresh(){
    const q   = (qEl.value || '').trim().toLowerCase();
    const st  = (stEl.value || '').trim().toLowerCase();
    const sel = cards.filter(c => matches(c, q, st));
    applySort(sel, sortEl.value);
    render(sel);
  }

  qEl?.addEventListener('input', refresh);
  stEl?.addEventListener('change', refresh);
  sortEl?.addEventListener('change', refresh);

  refresh();
</script>
@endsection
