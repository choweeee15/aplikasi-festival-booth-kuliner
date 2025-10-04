<?php

namespace App\Http\Controllers;

use App\Models\Booth;
use App\Models\Tiket;
use App\Models\PembelianTiket;
use App\Models\Pengunjung;

class HomeController extends Controller
{
    public function index()
    {
        $featuredBooths   = Booth::orderByDesc('id')->take(6)->get();
        $tiketList        = Tiket::orderBy('harga')->get();

        $countBooth       = Booth::count();
        $countTiketJenis  = Tiket::count();
        $countPengunjung  = Pengunjung::count();
        $tiketTerjual     = (int) PembelianTiket::where('status_bayar','lunas')->sum('jumlah');

        return view('home', compact(
            'featuredBooths','tiketList',
            'countBooth','countTiketJenis','countPengunjung','tiketTerjual'
        ));
    }
}
