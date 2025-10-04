<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Pengunjung;
use App\Models\Penyewa;
use App\Models\PembelianTiket;
use App\Models\TransaksiSewa;

class UserAccountController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user(); // aman dipanggil karena route pakai middleware auth

        // Coba sinkron ke entitas domain lewat email (kalau belum ada, hasilnya null)
        $pengunjung = $user?->email
            ? Pengunjung::where('email', $user->email)->first()
            : null;

        $penyewa = $user?->email
            ? Penyewa::where('email', $user->email)->first()
            : null;

        // Daftar pembelian tiket user (kalau belum terdaftar sebagai pengunjung -> kosong)
        $pembelian = PembelianTiket::with(['tiket'])
            ->when($pengunjung, fn($q) => $q->where('pengunjung_id', $pengunjung->id))
            ->orderByDesc('id')
            ->paginate(5)
            ->withQueryString();

        // Daftar transaksi sewa booth user (kalau belum terdaftar sebagai penyewa -> kosong)
        $sewas = TransaksiSewa::with(['booth'])
            ->when($penyewa, fn($q) => $q->where('penyewa_id', $penyewa->id))
            ->orderByDesc('id')
            ->paginate(5)
            ->withQueryString();

        // Ringkasan
        $summary = [
            'total_pembelian' => $pengunjung
                ? PembelianTiket::where('pengunjung_id', $pengunjung->id)->count()
                : 0,
            'tiket_lunas'     => $pengunjung
                ? PembelianTiket::where('pengunjung_id', $pengunjung->id)->where('status_bayar','lunas')->count()
                : 0,
            'total_sewa'      => $penyewa
                ? TransaksiSewa::where('penyewa_id', $penyewa->id)->count()
                : 0,
        ];

        return view('user_account', compact(
            'user','pengunjung','penyewa','pembelian','sewas','summary'
        ));
    }
}
