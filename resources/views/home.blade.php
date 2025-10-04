@extends('layouts.main')
@section('title', 'Home – Booth Festival')

@section('content')

<!-- Spinner -->
<div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
  <div class="spinner-border text-danger" style="width:3rem;height:3rem" role="status">
    <span class="sr-only">Loading...</span>
  </div>
</div>

<!-- HERO -->
<div class="container-fluid p-0 mb-5">
  <div id="heroFestival" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
      <div class="carousel-item active">
        <img class="w-100" src="/img/festival1.jpg" alt="Festival">
        <div class="carousel-caption d-flex align-items-center">
          <div class="container">
            <div class="row align-items-center justify-content-center justify-content-lg-start">
              <div class="col-11 col-lg-7 text-center text-lg-start">
                <h6 class="text-white text-uppercase mb-3 animated slideInDown">// Festival Kuliner & UMKM //</h6>
                <h1 class="display-5 text-white mb-4 pb-2 animated slideInDown">Jelajahi Booth Favorit dan Nikmati Pengalaman Seru</h1>
                <a href="{{ route('pembelian-tiket.index') }}" class="btn btn-danger py-3 px-5 animated slideInDown">Beli Tiket Sekarang <i class="fa fa-arrow-right ms-2"></i></a>
              </div>
              <div class="col-lg-5 d-none d-lg-flex animated zoomIn">
                <img class="img-fluid" src="/img/booth1.png" alt="Booth Illustration">
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="carousel-item">
        <img class="w-100" src="/img/festival2.jpg" alt="Festival">
        <div class="carousel-caption d-flex align-items-center">
          <div class="container">
            <div class="row align-items-center justify-content-center justify-content-lg-start">
              <div class="col-11 col-lg-7 text-center text-lg-start">
                <h6 class="text-white text-uppercase mb-3">// E-Ticket & QR Gate //</h6>
                <h1 class="display-5 text-white mb-4 pb-2">Masuk Lebih Cepat dengan E-Ticket & QR Code</h1>
                <a href="{{ route('user.booth.katalog') }}" class="btn btn-danger py-3 px-5">Lihat Booth <i class="fa fa-arrow-right ms-2"></i></a>
              </div>
              <div class="col-lg-5 d-none d-lg-flex animated zoomIn">
                <img class="img-fluid" src="/img/qrilustrasi.png" alt="QR Illustration">
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#heroFestival" data-bs-slide="prev">
      <span class="carousel-control-prev-icon"></span>
      <span class="visually-hidden">Sebelumnya</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#heroFestival" data-bs-slide="next">
      <span class="carousel-control-next-icon"></span>
      <span class="visually-hidden">Selanjutnya</span>
    </button>
  </div>
</div>

<!-- COUNTERS -->
<div class="container-fluid bg-dark my-5 py-5">
  <div class="container">
    <div class="row g-4 text-center">
      <div class="col-6 col-md-3">
        <i class="fa fa-store fa-2x text-white mb-2"></i>
        <h2 class="text-white mb-1">{{ $countBooth }}</h2>
        <p class="text-white-50 mb-0">Booth</p>
      </div>
      <div class="col-6 col-md-3">
        <i class="fa fa-ticket-alt fa-2x text-white mb-2"></i>
        <h2 class="text-white mb-1">{{ $countTiketJenis }}</h2>
        <p class="text-white-50 mb-0">Jenis Tiket</p>
      </div>
      <div class="col-6 col-md-3">
        <i class="fa fa-users fa-2x text-white mb-2"></i>
        <h2 class="text-white mb-1">{{ $countPengunjung }}</h2>
        <p class="text-white-50 mb-0">Pengunjung Terdaftar</p>
      </div>
      <div class="col-6 col-md-3">
        <i class="fa fa-check fa-2x text-white mb-2"></i>
        <h2 class="text-white mb-1">{{ $tiketTerjual }}</h2>
        <p class="text-white-50 mb-0">Tiket Terjual</p>
      </div>
    </div>
  </div>
</div>

<!-- BOOTH POPULER -->
<div class="container-xxl py-5">
  <div class="container">
    <div class="text-center mb-5">
      <h6 class="text-danger text-uppercase">// Booth Populer //</h6>
      <h1 class="mb-3">Jelajahi Booth Pilihan</h1>
      <p class="text-muted">Temukan makanan, minuman, dan produk lokal favorit di sini.</p>
    </div>

    <div class="row g-4">
      @forelse($featuredBooths as $booth)
      <div class="col-md-6 col-lg-4">
        <div class="card h-100 shadow border-0">
          <img src="/img/booth-placeholder.jpg" class="card-img-top" alt="Booth">
          <div class="card-body">
            <h5 class="card-title mb-1">{{ $booth->nama_booth }}</h5>
            <p class="text-muted mb-2">{{ \Illuminate\Support\Str::limit($booth->deskripsi, 80) }}</p>
            <div class="d-flex align-items-center justify-content-between">
              <span class="fw-bold text-danger">Rp {{ number_format($booth->harga_sewa,0,',','.') }}</span>
              <span class="badge bg-{{ $booth->status=='tersedia'?'success':'secondary' }}">{{ ucfirst($booth->status) }}</span>
            </div>
          </div>
          <div class="card-footer bg-white border-0">
            <a href="{{ route('user.booth.sewa.create', $booth->id) }}" class="btn btn-sm btn-danger w-100">
              <i class="fas fa-handshake me-1"></i> Ajukan Sewa
            </a>
          </div>
        </div>
      </div>
      @empty
      <div class="col-12 text-center text-muted">Belum ada booth.</div>
      @endforelse
    </div>

    <div class="text-center mt-4">
      <a href="{{ route('user.booth.katalog') }}" class="btn btn-outline-danger px-4">
        Lihat Semua Booth
      </a>
    </div>
  </div>
</div>

<!-- TIKET -->
<div class="container-xxl py-5 bg-light">
  <div class="container">
    <div class="text-center mb-5">
      <h6 class="text-danger text-uppercase">// Tiket Festival //</h6>
      <h1 class="mb-3">Pilih Tiketmu</h1>
      <p class="text-muted">E-ticket dengan QR code. Tunjukkan di gate, masuk tanpa antre lama.</p>
    </div>

    <div class="row g-4">
      @forelse($tiketList as $t)
      <div class="col-md-6 col-lg-4">
        <div class="card h-100 shadow border-0">
          <div class="card-body">
            <h5 class="card-title">{{ $t->nama_tiket }}</h5>
            <p class="mb-1"><i class="fas fa-tag me-2 text-danger"></i>Harga: <b>Rp {{ number_format($t->harga,0,',','.') }}</b></p>
            <p class="mb-2"><i class="fas fa-box-open me-2 text-danger"></i>Stok: <b>{{ $t->stok }}</b></p>
            <p class="small text-muted mb-0">E-ticket akan muncul di “Pembelian Tiket”.</p>
          </div>
          <div class="card-footer bg-white border-0">
            <a href="{{ route('pembelian-tiket.index') }}" class="btn btn-sm btn-danger w-100">
              <i class="fas fa-shopping-cart me-1"></i> Beli Tiket
            </a>
          </div>
        </div>
      </div>
      @empty
      <div class="col-12 text-center text-muted">Tiket belum tersedia.</div>
      @endforelse
    </div>
  </div>
</div>

<!-- CARA MASUK -->
<div class="container-xxl py-5">
  <div class="container">
    <div class="text-center mb-5">
      <h6 class="text-danger text-uppercase">// Cara Masuk //</h6>
      <h1 class="mb-3">3 Langkah Mudah</h1>
    </div>
    <div class="row g-4">
      <div class="col-md-4 text-center">
        <div class="p-4 border rounded-3 h-100">
          <i class="fas fa-ticket-alt fa-2x text-danger mb-3"></i>
          <h5>Beli Tiket</h5>
          <p class="text-muted mb-0">Pilih tiket dan selesaikan pembayaran.</p>
        </div>
      </div>
      <div class="col-md-4 text-center">
        <div class="p-4 border rounded-3 h-100">
          <i class="fas fa-mobile-alt fa-2x text-danger mb-3"></i>
          <h5>Dapatkan E-Ticket</h5>
          <p class="text-muted mb-0">QR code muncul di halaman “Pembelian Tiket”.</p>
        </div>
      </div>
      <div class="col-md-4 text-center">
        <div class="p-4 border rounded-3 h-100">
          <i class="fas fa-qrcode fa-2x text-danger mb-3"></i>
          <h5>Scan di Gate</h5>
          <p class="text-muted mb-0">Tunjukkan QR, masuk, dan nikmati festival.</p>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- TESTIMONI -->
<div class="container-xxl py-5 bg-light">
  <div class="container">
    <div class="text-center mb-5">
      <h6 class="text-danger text-uppercase">// Testimoni //</h6>
      <h1 class="mb-3">Apa Kata Pengunjung?</h1>
    </div>
    <div class="row g-4">
      <div class="col-md-4"><div class="p-4 border rounded-3 h-100 bg-white"><p class="mb-3">“Tiket QR praktis banget, masuknya cepet!”</p><div class="small text-muted">— Rani</div></div></div>
      <div class="col-md-4"><div class="p-4 border rounded-3 h-100 bg-white"><p class="mb-3">“Booth kulinernya lengkap, suasananya asik.”</p><div class="small text-muted">— Bagas</div></div></div>
      <div class="col-md-4"><div class="p-4 border rounded-3 h-100 bg-white"><p class="mb-3">“Transaksi sewa booth gampang, admin responsif.”</p><div class="small text-muted">— Sari (Penyewa)</div></div></div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
  window.addEventListener('load', () => {
    document.getElementById('spinner')?.classList.remove('show');
  });
</script>
@endpush
