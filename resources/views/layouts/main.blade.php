<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>@yield('title', 'FestivalKita – Booth Festival')</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <!-- HEAD -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <link href="{{ asset('img/favicon.ico') }}" rel="icon">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@600;700&family=Ubuntu:wght@400;500&display=swap" rel="stylesheet">

    <!-- Icon Fonts -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries -->
    <link href="{{ asset('lib/animate/animate.min.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css') }}" rel="stylesheet">

    <!-- Bootstrap -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Main Style -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">

    @yield('dashboard-css')
</head>
<body>

    {{-- TOPBAR (global) --}}
    <div class="container-fluid bg-light py-2 d-none d-lg-block">
      <div class="container d-flex justify-content-between">
        <div class="small">
          <i class="fa fa-map-marker-alt text-danger me-2"></i> Lokasi: Area Festival, Kota Kamu
          <span class="mx-2">•</span>
          <i class="far fa-clock text-danger me-2"></i> Buka: 10.00 – 22.00
        </div>
        <div class="small">
          <i class="fa fa-phone-alt text-danger me-2"></i> Kontak: 08xx-xxxx-xxxx
        </div>
      </div>
    </div>

    {{-- NAVBAR (global) --}}
    <nav class="navbar navbar-expand-lg bg-white navbar-light shadow sticky-top p-0">
      <a href="{{ route('user.home') }}" class="navbar-brand d-flex align-items-center px-4 px-lg-5">
        <h2 class="m-0 text-danger"><i class="fa fa-ticket-alt me-3"></i>FestivalKita</h2>
      </a>
      <button class="navbar-toggler me-4" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarCollapse">
        <div class="navbar-nav ms-auto p-4 p-lg-0">
          <a href="{{ route('user.home') }}" class="nav-item nav-link {{ request()->routeIs('user.home') ? 'active' : '' }}">
            <i class="fas fa-home me-2"></i> Home
          </a>
          <a href="{{ route('pembelian-tiket.index') }}" class="nav-item nav-link {{ request()->routeIs('pembelian-tiket.*') ? 'active' : '' }}">
            <i class="fas fa-ticket-alt me-2"></i> Beli Tiket
          </a>
          <a href="{{ route('user.booth.katalog') }}" class="nav-item nav-link {{ request()->routeIs('user.booth.*') ? 'active' : '' }}">
            <i class="fas fa-store me-2"></i> Booth
          </a>
          <a href="{{ route('user.account') }}" 
            class="nav-item nav-link {{ request()->routeIs('user.account') ? 'active' : '' }}">
            <i class="fas fa-user me-2"></i> Akun
          </a>
        </div>

        @auth
          <form method="POST" action="{{ route('logout') }}" class="d-inline me-3">
            @csrf
            <button class="btn btn-link nav-item nav-link text-dark" style="text-decoration:none;">
              <i class="fas fa-sign-out-alt me-1"></i> Logout
            </button>
          </form>
        @endauth

        @guest
          <div class="d-flex me-3">
            @if(Route::has('login'))
              <a href="{{ route('login') }}" class="nav-item nav-link">Login</a>
            @endif
            @if(Route::has('register'))
              <a href="{{ route('register') }}" class="nav-item nav-link">Register</a>
            @endif
          </div>
        @endguest
      </div>
    </nav>

    <main>
        @yield('content')
    </main>

    @includeIf('layouts.footer')

    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script> AOS.init(); </script>

    @yield('dashboard-js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</body>
</html>
