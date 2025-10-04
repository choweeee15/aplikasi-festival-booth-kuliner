<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>@yield('title', 'Bengkel Admin')</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;700&display=swap" rel="stylesheet">

    <!-- Bootstrap & Icon Fonts -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <!-- Libraries -->
    <link href="{{ asset('lib/animate/animate.min.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/owlcarousel/assets/owl.carousel.min.css') }}" rel="stylesheet">
    <link href="{{ asset('lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css') }}" rel="stylesheet">

    <!-- Custom Styles -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">

    @yield('dashboard-css')

    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Montserrat', sans-serif;
        }
        .sidebar {
            background: linear-gradient(180deg, #5a0000, #2e0000);
            color: white;
            padding: 20px 0;
            height: 100vh;
            position: sticky;
            top: 0;
            border-right: 2px solid #770000;
            box-shadow: 4px 0 10px rgba(0, 0, 0, 0.2);
            font-family: 'Montserrat', sans-serif;
        }
        .sidebar h4 {
            font-weight: 700;
            margin-bottom: 30px;
            color: #f8d7da;
            text-align: center;
        }
        .menu-link {
            display: flex;
            align-items: center;
            padding: 10px 20px;
            color: #f0f0f0;
            text-decoration: none;
            transition: all 0.2s ease;
            font-size: 15px;
            border-left: 3px solid transparent;
            font-weight: 500;
        }
        .menu-link:hover,
        .menu-link.active {
            background-color: rgba(255, 255, 255, 0.08);
            color: #ffffff;
            border-left: 3px solid #ff4d4d;
            font-weight: 700;
        }
        .menu-section {
            margin: 12px 0 6px 20px;
            color: #fff;
            font-weight: 700;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .logout-btn {
            background-color: #b30000;
            color: white;
            padding: 12px 20px;
            border: none;
            width: 100%;
            text-align: left;
            transition: background 0.3s;
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
        }
        .logout-btn:hover { background-color: #8b0000; }
        .dashboard-container {
            padding: 30px;
            background: linear-gradient(135deg, #4e0000, #800000);
            min-height: 100vh;
            animation: fadeInScale 1s ease forwards;
            color: #f8f8f8;
        }
        @keyframes fadeInScale {
            0% { opacity: 0; transform: scale(0.95); }
            100% { opacity: 1; transform: scale(1); }
        }
        .btn-gradient-red {
            background: linear-gradient(45deg, #b30000, #7b0000);
            color: #fff;
            border: none;
            transition: background 0.3s ease;
        }
        .btn-gradient-red:hover {
            background: linear-gradient(45deg, #d10000, #a30000);
            color: white;
        }
        table.table {
            background: #fff; color: #000; border-radius: 8px; overflow: hidden;
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">

        <!-- Sidebar -->
        <nav class="col-md-3 col-lg-2 sidebar d-flex flex-column">
            <h4>🎪 Admin Festival</h4>

            {{-- DASHBOARD --}}
            <div class="menu-section">Dashboard</div>
            <a href="/dashboardadmin" class="menu-link {{ request()->is('dashboardadmin') ? 'active' : '' }}">
                <i class="fas fa-home me-2"></i> Dashboard
            </a>
            <a href="{{ route('user.index') }}" class="menu-link {{ request()->routeIs('user.*') ? 'active' : '' }}">
                <i class="fas fa-users me-2"></i> Kelola Pengguna
            </a>
            

            {{-- OPERASIONAL --}}
            <div class="menu-section">Operasional</div>
            <a href="{{ route('gate.scan') }}" class="menu-link {{ request()->routeIs('gate.scan*') ? 'active' : '' }}">
                <i class="fas fa-qrcode me-2"></i> Gate Scan
            </a>
                        
            <a href="{{ route('admin.pembelian-tiket.index') }}" 
                class="menu-link {{ request()->routeIs('admin.pembelian-tiket.*') ? 'active' : '' }}">
                <i class="fas fa-ticket-alt me-2"></i> Kelola Pembelian Tiket
            </a>
            <a href="{{ route('admin.transaksi-sewa.index') }}" 
                class="menu-link {{ request()->routeIs('admin.transaksi-sewa.*') ? 'active' : '' }}">
                <i class="fas fa-handshake me-2"></i> Kelola Sewa Booth
            </a>
            <a href="{{ route('tiket.index') }}" class="menu-link {{ request()->routeIs('tiket.*') ? 'active' : '' }}">
                <i class="fas fa-ticket-alt me-2"></i> Kelola Tiket
              </a>
            <a href="{{ route('booths.index') }}" class="menu-link {{ request()->routeIs('booths.*') ? 'active' : '' }}">
                <i class="fas fa-store me-2"></i> Kelola Booth
              </a>
              <a href="{{ route('penyewas.index') }}" class="menu-link {{ request()->routeIs('penyewas.*') ? 'active' : '' }}">
                <i class="fas fa-user-tie me-2"></i> Kelola Penyewa
              </a>
              <a href="{{ route('pengunjungs.index') }}" class="menu-link {{ request()->routeIs('pengunjungs.*') ? 'active' : '' }}">
                <i class="fas fa-users me-2"></i> Kelola Pengunjung
              </a>
              <a href="{{ route('transaksi-sewa.index') }}" class="menu-link {{ request()->routeIs('transaksi-sewa.*') ? 'active' : '' }}">
                <i class="fas fa-handshake me-2"></i> Transaksi Sewa
              </a>
              
              

            {{-- LAPORAN (opsional, jika butuh rekap cepat) --}}
            {{-- <div class="menu-section">Laporan</div>
            <a href="{{ route('laporan.index') }}" class="menu-link {{ request()->routeIs('laporan.*') ? 'active' : '' }}">
                <i class="fas fa-chart-line me-2"></i> Ringkasan Penjualan
            </a> --}}

            {{-- TOMBOL HOME (jika masih dipakai untuk front) --}}
            <a href="/home" class="btn btn-gradient-red my-3 mx-3 text-center">
                <i class="fas fa-house me-2"></i> Home Pengguna
            </a>

            {{-- LOGOUT --}}
            <form method="POST" action="{{ route('logout') }}" class="mt-auto px-3">
                @csrf
                <button type="submit" class="logout-btn">
                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                </button>
            </form>
        </nav>

        <!-- Main Content -->
        <main class="col-md-9 col-lg-10 p-4">
            @yield('content')
        </main>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script> AOS.init(); </script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

@yield('dashboard-js')

<style>
    .dashboard-container{padding:30px;background:linear-gradient(135deg,#4e0000,#800000);min-height:100vh;color:#f8f8f8;animation:fadeInScale 1s ease forwards}
    @keyframes fadeInScale{0%{opacity:0;transform:scale(.95)}100%{opacity:1;transform:scale(1)}}
    .title-heading{font-size:2rem;font-weight:700;color:#fff}
    .card-dark{background:#5a0000;border:none;color:#fff;border-radius:12px;box-shadow:0 8px 20px rgba(255,0,0,.2)}
    .table{background:#fff;color:#000;border-radius:8px;overflow:hidden;box-shadow:0 8px 20px rgba(255,0,0,.2)}
    .table thead th{background:linear-gradient(45deg,#7b0000,#b30000);color:#fff;border:none;vertical-align:middle}
    .table tbody tr:hover{background:#ffe6e6}
    .btn-gradient-red{background:linear-gradient(45deg,#b30000,#7b0000);color:#fff;border:none}
    .btn-gradient-red:hover{background:linear-gradient(45deg,#d10000,#a30000)}
    .summary .card{background:#7b0000;color:#fff;border:none;border-radius:12px}
  </style>
  
</body>
</html>
