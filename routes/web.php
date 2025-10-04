<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ServiceRequestController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\KategoriKendaraanController;
use App\Http\Controllers\LayananController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\DashboardAdminController;
use App\Http\Controllers\LogActivityController;
use App\Http\Controllers\BoothController;
use App\Http\Controllers\TiketController;
use App\Http\Controllers\PenyewaController;
use App\Http\Controllers\PengunjungController;
use App\Http\Controllers\PembelianTiketController;
use App\Http\Controllers\TransaksiSewaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserBoothController;
use App\Http\Controllers\AdminBoothController;
use App\Http\Controllers\AdminTiketController;
use App\Http\Controllers\AdminPenyewaController;
use App\Http\Controllers\AdminPengunjungController;
use App\Http\Controllers\AdminPembelianTiketController;
use App\Http\Controllers\AdminTransaksiSewaController;
use App\Http\Controllers\UserAccountController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\GateScanController;

Route::middleware(['auth'])->group(function () {
    Route::get('/gate/scan', [GateScanController::class, 'index'])->name('gate.scan');
    Route::post('/gate/scan/verify', [GateScanController::class, 'verify'])->name('gate.scan.verify');
    Route::get('/gate/verify', [GateScanController::class, 'verifyGet'])->name('gate.verify');
});

// katalog booth user
Route::get('/user/booth/katalog', [UserBoothController::class, 'katalog'])
     ->name('user.booth.katalog');

// form sewa booth (hanya kalau punya tiket lunas)
Route::get('/user/booth/{booth}/sewa', [UserBoothController::class, 'create'])
     ->name('user.booth.sewa.create');

// proses simpan sewa booth (kalau sudah ada logicnya)
Route::post('/user/booth/{booth}/sewa', [UserBoothController::class, 'store'])
     ->name('user.booth.sewa.store');

// Bungkus auth kalau perlu
Route::middleware(['web'/*,'auth','can:admin'*/])->group(function () {
    // resource admin – url /admin/... tapi nama route tetap booths.*, tiket.*, dst
    Route::resource('/admin/booths', BoothController::class)->names('booths');
    Route::resource('/admin/tiket', TiketController::class)->names('tiket');
    Route::resource('/admin/penyewas', PenyewaController::class)->names('penyewas');
    Route::resource('/admin/pengunjungs', PengunjungController::class)->names('pengunjungs');
    Route::resource('/admin/transaksi-sewa', TransaksiSewaController::class)->names('transaksi-sewa');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/admin/transaksi-sewa', [AdminTransaksiSewaController::class, 'index'])
        ->name('admin.transaksi-sewa.index');
    Route::post('/admin/transaksi-sewa/{id}/approve', [AdminTransaksiSewaController::class, 'approve'])
        ->name('admin.transaksi-sewa.approve');
    Route::post('/admin/transaksi-sewa/{id}/reject', [AdminTransaksiSewaController::class, 'reject'])
        ->name('admin.transaksi-sewa.reject');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/pembelian-tiket',        [PembelianTiketController::class, 'index'])->name('pembelian-tiket.index');
    Route::get('/pembelian-tiket/create', [PembelianTiketController::class, 'create'])->name('pembelian-tiket.create');
    Route::post('/pembelian-tiket',       [PembelianTiketController::class, 'store'])->name('pembelian-tiket.store');
    Route::get('/pembelian-tiket/{id}',   [PembelianTiketController::class, 'show'])->name('pembelian-tiket.show');

    // ⬇ QR dinamis (now SVG)
    Route::get('/pembelian-tiket/{id}/qr', [PembelianTiketController::class, 'qr'])->name('pembelian-tiket.qr');
});

// Halaman Utama
Route::get('/', function () {
    return view('auth.login');
});

// Auth
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Halaman Publik
Route::get('/home', [AuthController::class, 'home'])->name('user.home');
Route::get('/about', [HomeController::class, 'about']);
Route::get('/services', [HomeController::class, 'services'])->name('services');


Route::get('/', [HomeController::class, 'index'])->name('landing'); // opsional
Route::get('/home', [HomeController::class, 'index'])->name('user.home');

// Booking
Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');

// Service Requests
Route::get('/service-requests', [ServiceRequestController::class, 'index'])->name('service.requests');
Route::post('/service-requests', [ServiceRequestController::class, 'store'])->name('service.requests.store');
Route::put('/service-requests/{id}/status', [ServiceRequestController::class, 'updateStatus'])->name('service.requests.updateStatus');

// Pengguna
Route::get('/users', [AuthController::class, 'listUsers'])->name('users');
Route::get('/user', [AuthController::class, 'listUsers'])->name('user.index');
Route::get('/user/create', [AuthController::class, 'createUser'])->name('user.create');
Route::post('/user', [AuthController::class, 'storeUser'])->name('user.store');
Route::get('/user/{id}/edit', [AuthController::class, 'editUser'])->name('user.edit');
Route::put('/user/{id}', [AuthController::class, 'updateUser'])->name('user.update');
Route::delete('/user/{id}', [AuthController::class, 'destroyUser'])->name('user.destroy');

// Mekanik
Route::get('/mekanik', [AuthController::class, 'mekanik'])->name('mekanik.index');
Route::get('/mekanik/create', [AuthController::class, 'create'])->name('mekanik.create');
Route::post('/mekanik', [AuthController::class, 'store'])->name('mekanik.store');
Route::get('/mekanik/{id}/edit', [AuthController::class, 'edit'])->name('mekanik.edit');
Route::put('/mekanik/{id}', [AuthController::class, 'update'])->name('mekanik.update');
Route::delete('/mekanik/{id}', [AuthController::class, 'destroy'])->name('mekanik.destroy');

Route::get('/service', [LayananController::class, 'index'])->name('layanan.index');
Route::post('/service', [LayananController::class, 'store'])->name('layanan.store');
Route::put('/service/{id}', [LayananController::class, 'update'])->name('layanan.update');
Route::delete('/service/{id}', [LayananController::class, 'destroy'])->name('layanan.destroy');

Route::get('/export-pdf', [PembayaranController::class, 'exportPdf'])->name('pembayaran.exportPdf');
Route::get('/export-excel', [PembayaranController::class, 'exportExcel'])->name('pembayaran.exportExcel'); 

// Pembayaran routes for users and admins
Route::post('/pembayaran/{id}/upload', [PembayaranController::class, 'upload'])->name('pembayaran.upload');
Route::put('/pembayaran/{id}/verifikasi', [PembayaranController::class, 'verifikasi'])->name('pembayaran.verifikasi');

Route::get('/dashboardadmin', [DashboardAdminController::class, 'index'])->name('dashboardadmin');

Route::get('/log_activity', [LogActivityController::class, 'index'])->name('log_activity.index');

Route::get('/dashboardadmin', [DashboardController::class, 'index'])->name('dashboardadmin');

Route::get('/booth', [UserBoothController::class, 'katalog'])->name('user.booth.katalog');
Route::get('/booth/{booth}/sewa', [UserBoothController::class, 'create'])->name('user.booth.sewa.create');
Route::post('/booth/{booth}/sewa', [UserBoothController::class, 'store'])->name('user.booth.sewa.store');

// HOME pengguna (sudah kamu punya)
Route::get('/home', [HomeController::class, 'index'])->name('user.home');

// KATALOG & SEWA BOOTH (kalau sudah dibuat sebelumnya)
Route::get('/booth', [UserBoothController::class, 'katalog'])->name('user.booth.katalog');
Route::get('/booth/{booth}/sewa', [UserBoothController::class, 'create'])->name('user.booth.sewa.create');
Route::post('/booth/{booth}/sewa', [UserBoothController::class, 'store'])->name('user.booth.sewa.store');

// ====== USER: AKUN (PENGUNJUNG) ======
Route::get('/pengunjungs', [PengunjungController::class, 'index'])->name('pengunjungs.index');
Route::post('/pengunjungs', [PengunjungController::class, 'storeOrUpdate'])->name('pengunjungs.store');

// ====== USER: BELI TIKET ======
Route::get('/pembelian-tiket', [PembelianTiketController::class, 'index'])->name('pembelian-tiket.index');
Route::get('/pembelian-tiket/create', [PembelianTiketController::class, 'create'])->name('pembelian-tiket.create');
Route::post('/pembelian-tiket', [PembelianTiketController::class, 'store'])->name('pembelian-tiket.store');
// Route::get('/pembelian-tiket/{id}',   [PembelianTiketController::class, 'show'])->name('pembelian-tiket.show');

Route::middleware(['auth'])->group(function () {
    Route::get('/pembelian-tiket',        [PembelianTiketController::class, 'index'])->name('pembelian-tiket.index');
    Route::get('/pembelian-tiket/create', [PembelianTiketController::class, 'create'])->name('pembelian-tiket.create');
    Route::post('/pembelian-tiket',       [PembelianTiketController::class, 'store'])->name('pembelian-tiket.store');
    Route::get('/pembelian-tiket/{id}',   [PembelianTiketController::class, 'show'])->name('pembelian-tiket.show');
});
// Boleh dibungkus middleware auth/is_admin kalau sudah ada
Route::get('/admin/booths',            [AdminBoothController::class, 'index'])->name('admin.booths.index');
Route::get('/admin/tiket',             [AdminTiketController::class, 'index'])->name('admin.tiket.index');
Route::get('/admin/penyewas',          [AdminPenyewaController::class, 'index'])->name('admin.penyewas.index');
Route::get('/admin/pengunjungs',       [AdminPengunjungController::class, 'index'])->name('admin.pengunjungs.index');
Route::get('/admin/pembelian-tiket',   [AdminPembelianTiketController::class, 'index'])->name('admin.pembelian-tiket.index');
Route::get('/admin/transaksi-sewa',    [AdminTransaksiSewaController::class, 'index'])->name('admin.transaksi-sewa.index');

// ===== ADMIN: Kelola Pengunjung & Kelola Pembelian Tiket =====
Route::get('/admin/pengunjungs', [AdminPengunjungController::class, 'index'])
    ->name('admin.pengunjungs.index');

Route::get('/admin/pembelian-tiket', [AdminPembelianTiketController::class, 'index'])
    ->name('admin.pembelian-tiket.index');

    Route::middleware('auth')->group(function () {
        Route::get('/akun', [UserAccountController::class, 'index'])->name('user.account');
    });
   
Route::middleware(['web', /* 'auth', 'can:admin' */])->group(function () {
        Route::get('/admin/users',        [AdminUserController::class, 'index'])->name('user.index');
        Route::post('/admin/users',       [AdminUserController::class, 'store'])->name('user.store');
        Route::put('/admin/users/{id}',   [AdminUserController::class, 'update'])->name('user.update');
        Route::delete('/admin/users/{id}',[AdminUserController::class, 'destroy'])->name('user.destroy');
    });

Route::resources([
    'booths'           => BoothController::class,
    'tiket'            => TiketController::class,
    'penyewas'         => PenyewaController::class,
    'pengunjungs'      => PengunjungController::class,
    'pembelian-tiket'  => PembelianTiketController::class,
    'transaksi-sewa'   => TransaksiSewaController::class,
]);

Route::middleware(['auth'])->group(function () {
    Route::post('/admin/pembelian-tiket/{id}/approve', [AdminPembelianTiketController::class, 'approve'])->name('admin.pembelian-tiket.approve');
    Route::post('/admin/pembelian-tiket/{id}/reject',  [AdminPembelianTiketController::class, 'reject'])->name('admin.pembelian-tiket.reject');
});

// Dashboard Khusus for authenticated users
Route::middleware(['auth'])->group(function () {
    Route::get('/riwayat-pembayaran-admin', [PembayaranController::class, 'indexadmin'])->name('riwayat.pembayaran.admin');
    Route::get('/riwayat-pembayaran', [PembayaranController::class, 'index'])->name('riwayat.pembayaran');
    Route::get('/booking-history', [BookingController::class, 'history'])->name('booking.history');
    // Route::get('/dashboardadmin', [AuthController::class, 'dashboardadmin'])->name('dashboard.admin');

    Route::get('/category', [KategoriKendaraanController::class, 'index'])->name('category.index');
    Route::post('/category', [KategoriKendaraanController::class, 'store'])->name('category.store');
    Route::put('/category/{id}', [KategoriKendaraanController::class, 'update'])->name('category.update');
    Route::delete('/category/{id}', [KategoriKendaraanController::class, 'destroy'])->name('category.destroy');

    Route::resource('category', KategoriKendaraanController::class);

    Route::get('/pengguna/dashboard', function () {
        return view('pengguna.dashboard');
    })->name('pengguna.dashboard');
});
