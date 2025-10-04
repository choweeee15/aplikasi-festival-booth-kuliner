<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Registrasi;
use App\Models\Pembayaran;
use App\Models\Layanan;
use App\Models\Mekanik;
use App\Models\KategoriKendaraan;
use App\Models\LogActivity; // Pastikan LogActivity model diimport

class DashboardAdminController extends Controller
{
    public function index()
    {
        // Hitung total data
        $totalUsers = User::count();
        $totalRegistrations = Registrasi::count();
        $totalPayments = Pembayaran::count();
        $totalServices = Layanan::count();
        $totalMechanics = Mekanik::count();
        $totalCategories = KategoriKendaraan::count();

        // Log aktivitas: Admin mengakses dashboard
        LogActivity::create([
            'username' => auth()->user()->email,  // Mengambil email admin yang sedang login
            'activity' => 'Mengakses halaman dashboard admin.',
            'ip_address' => request()->ip(),  // Mengambil alamat IP pengguna
        ]);

        // Mengirim data ke view
        return view('dashboardadmin', compact(
            'totalUsers', 
            'totalRegistrations', 
            'totalPayments', 
            'totalServices', 
            'totalMechanics', 
            'totalCategories'
        ));
    }
}
