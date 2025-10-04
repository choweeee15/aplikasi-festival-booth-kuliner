<?php

namespace App\Http\Controllers;

use App\Models\PembelianTiket;
use Illuminate\Http\Request;

class AdminPembelianTiketController extends Controller
{
    public function index(Request $request)
    {
        $email  = trim((string) $request->get('email', ''));
        $status = trim((string) $request->get('status', ''));

        $query = PembelianTiket::with(['tiket','pengunjung']);

        if ($email !== '') {
            $query->whereHas('pengunjung', fn($q) => $q->where('email', $email));
        }
        if (in_array($status, ['pending','lunas'], true)) {
            $query->where('status_bayar', $status);
        }

        $rows = $query->orderByDesc('id')->paginate(10)->withQueryString();

        $rekap = [
            'total'   => PembelianTiket::count(),
            'lunas'   => PembelianTiket::where('status_bayar','lunas')->count(),
            'pending' => PembelianTiket::where('status_bayar','pending')->count(),
        ];

        return view('admin_pembelian_tiket_index', compact('rows','rekap','email','status'));
    }

    public function approve($id)
    {
        $row = PembelianTiket::findOrFail($id);
        $row->status_bayar = 'lunas';
        // kalau tabel kamu tidak punya updated_at, jangan panggil touch() dsb
        $row->save();

        return back()->with('success', 'Pembayaran disetujui.');
    }

    public function reject($id)
    {
        $row = PembelianTiket::findOrFail($id);
        $row->status_bayar = 'pending';
        $row->save();

        return back()->with('success', 'Status diubah menjadi pending.');
    }
}
