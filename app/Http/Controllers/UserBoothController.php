<?php

namespace App\Http\Controllers;

use App\Models\Booth;
use App\Models\Pengunjung;
use App\Models\PembelianTiket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UserBoothController extends Controller
{
    /**
     * Katalog booth (list semua booth)
     */
    public function katalog()
    {
        $booths = Booth::orderBy('nama_booth')->get();
        // view kamu: resources/views/booth_katalog.blade.php (sudah ada)
        return view('booth_katalog', compact('booths'));
    }

    /**
     * Form sewa: hanya boleh jika user punya tiket 'lunas'
     */
    public function create(Booth $booth, Request $request)
    {
        // identifikasi pengunjung berdasarkan email user login
        $email = optional($request->user())->email;
        $pengunjung = $email ? Pengunjung::where('email', $email)->first() : null;

        if (!$pengunjung) {
            return redirect()->route('pembelian-tiket.index')
                ->with('error', 'Akun pengunjung tidak ditemukan. Gunakan email yang sama dengan saat pembelian tiket.');
        }

        $punyaTiketLunas = PembelianTiket::where('pengunjung_id', $pengunjung->id)
            ->where('status_bayar', 'lunas')
            ->exists();

        if (!$punyaTiketLunas) {
            return redirect()->route('pembelian-tiket.index', ['email' => $email])
                ->with('error', 'Kamu belum punya tiket yang lunas. Selesaikan pembelian tiket dulu ya.');
        }

        if (isset($booth->status) && $booth->status !== 'tersedia') {
            return back()->with('error', 'Booth ini sedang tidak tersedia.');
        }

        // form sewa kamu: resources/views/sewa_form.blade.php (sudah ada di project kamu)
        return view('sewa_form', compact('booth', 'pengunjung'));
    }

    /**
     * Simpan transaksi sewa
     * - Tanpa migrasi baru, kita isi kolom 'transaksi_sewa' yang tersedia secara dinamis.
     * - Update status booth => 'tersewa'
     */
    public function store(Booth $booth, Request $request)
{
    // validasi dasar (tanggal opsional – sesuaikan dengan form kamu)
    $data = $request->validate([
        'tanggal_mulai'   => 'nullable|date',
        'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
        'catatan'         => 'nullable|string|max:500',
    ]);

    // identifikasi pengunjung
    $email = optional($request->user())->email;
    $pengunjung = $email ? \App\Models\Pengunjung::where('email', $email)->first() : null;

    if (!$pengunjung) {
        return redirect()->route('pembelian-tiket.index')
            ->with('error', 'Akun pengunjung tidak ditemukan.');
    }

    // wajib punya tiket lunas
    $punyaTiketLunas = \App\Models\PembelianTiket::where('pengunjung_id', $pengunjung->id)
        ->where('status_bayar', 'lunas')
        ->exists();
    if (!$punyaTiketLunas) {
        return redirect()->route('pembelian-tiket.index', ['email' => $email])
            ->with('error', 'Kamu belum punya tiket yang lunas.');
    }

    if (isset($booth->status) && $booth->status !== 'tersedia') {
        return back()->with('error', 'Booth ini sudah tidak tersedia.');
    }

    // hitung durasi & total (fallback 1 hari)
    $durasi = 1;
    if (!empty($data['tanggal_mulai']) && !empty($data['tanggal_selesai'])) {
        $mulai   = \Carbon\Carbon::parse($data['tanggal_mulai'])->startOfDay();
        $selesai = \Carbon\Carbon::parse($data['tanggal_selesai'])->startOfDay();
        $durasi  = $mulai->diffInDays($selesai) + 1;
    }
    $total = (int)($booth->harga_sewa ?? 0) * max(1, $durasi);

    DB::transaction(function () use ($booth, $pengunjung, $data, $durasi, $total) {
        $table = 'transaksi_sewa';
        if (\Schema::hasTable($table)) {
            $cols = \Schema::getColumnListing($table);
            $payload = [];
        
            if (in_array('booth_id', $cols))        $payload['booth_id'] = $booth->id;
            if (in_array('pengunjung_id', $cols))   $payload['pengunjung_id'] = $pengunjung->id;
            if (in_array('tanggal_mulai', $cols))   $payload['tanggal_mulai'] = $data['tanggal_mulai'] ?? now()->toDateString();
            if (in_array('tanggal_selesai', $cols)) $payload['tanggal_selesai'] = $data['tanggal_selesai'] ?? now()->toDateString();
            if (in_array('durasi_hari', $cols))     $payload['durasi_hari'] = $durasi;
            if (in_array('total_biaya', $cols))     $payload['total_biaya'] = $total;
            if (in_array('total_bayar', $cols))     $payload['total_bayar'] = $total;
            if (in_array('status', $cols))          $payload['status'] = 'pending'; // ✅ FIX
            if (in_array('catatan', $cols) && !empty($data['catatan'])) $payload['catatan'] = $data['catatan'];
            if (in_array('created_at', $cols))      $payload['created_at'] = now();
        
            DB::table($table)->insert($payload);
        }
        

        $booth->status = 'tersewa';
        $booth->save();
    });

    return redirect()->route('user.booth.katalog')
        ->with('success', 'Pengajuan sewa berhasil dikirim. Mohon tunggu konfirmasi admin.');
}

}
