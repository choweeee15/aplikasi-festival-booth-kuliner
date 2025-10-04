<?php

namespace App\Http\Controllers;

use App\Models\Booth;
use App\Models\Tiket;
use App\Models\Penyewa;
use App\Models\Pengunjung;
use App\Models\PembelianTiket;
use App\Models\TransaksiSewa;

class DashboardController extends Controller
{
    public function index()
    {
        // Booth
        $totalBooth     = Booth::count();
        $boothTersedia  = Booth::where('status', 'tersedia')->count();
        $boothDisewa    = Booth::where('status', 'disewa')->count();

        // Tiket & Penjualan
        $totalJenisTiket = Tiket::count();
        $tiketTerjual    = (int) PembelianTiket::where('status_bayar', 'lunas')->sum('jumlah');
        $pendapatanTiket = (int) PembelianTiket::where('status_bayar', 'lunas')->sum('total_harga');

        // Penyewa & Pengunjung
        $totalPenyewa    = Penyewa::count();
        $totalPengunjung = Pengunjung::count();

        // Transaksi Sewa
        $sewaPending   = TransaksiSewa::where('status', 'pending')->count();
        $sewaDisetujui = TransaksiSewa::where('status', 'disetujui')->count();
        $sewaDitolak   = TransaksiSewa::where('status', 'ditolak')->count();

        // Terbaru
        $recentPembelian = PembelianTiket::orderByDesc('id')->limit(5)->get();
        $recentSewa      = TransaksiSewa::orderByDesc('id')->limit(5)->get();

        return view('dashboard', compact(
            'totalBooth','boothTersedia','boothDisewa',
            'totalJenisTiket','tiketTerjual','pendapatanTiket',
            'totalPenyewa','totalPengunjung',
            'sewaPending','sewaDisetujui','sewaDitolak',
            'recentPembelian','recentSewa'
        ));
    }
}
