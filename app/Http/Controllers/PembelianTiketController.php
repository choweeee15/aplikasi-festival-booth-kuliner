<?php

namespace App\Http\Controllers;

use App\Models\PembelianTiket;
use App\Models\Pengunjung;
use App\Models\Tiket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PembelianTiketController extends Controller
{
    /**
     * List milik user login saja
     */
    public function index(Request $request)
    {
        $emailLogin = optional($request->user())->email;
        $pengunjung = $emailLogin ? Pengunjung::where('email', $emailLogin)->first() : null;

        $items = collect();
        if ($pengunjung) {
            $items = PembelianTiket::with(['tiket','pengunjung'])
                ->where('pengunjung_id', $pengunjung->id)
                ->orderByDesc('id')
                ->get();
        }

        return view('pembelian_tiket_index', compact('items'));
    }

    /**
     * Form pembelian
     */
    public function create()
{
    // ambil semua tiket aktif/tersedia; sesuaikan kalau ada kolom 'status'
    $tiket = \App\Models\Tiket::orderBy('id')->get();

    return view('pembelian_tiket_create', compact('tiket'));
}


    /**
     * Simpan pembelian → status_bayar = pending, simpan bukti (di kolom qr_code sebagai bukti)
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'tiket_id' => 'required|exists:tiket,id',
            'jumlah'   => 'required|integer|min:1|max:10',
            'bukti'    => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'catatan'  => 'nullable|string|max:300',
        ]);

        $emailLogin = optional($request->user())->email;

        $pengunjung = Pengunjung::firstOrCreate(
            ['email' => $emailLogin],
            ['nama' => $emailLogin, 'password' => bcrypt(Str::random(12))]
        );

        DB::transaction(function () use ($data, $request, $pengunjung) {
            $t = Tiket::lockForUpdate()->findOrFail($data['tiket_id']);

            // kurangi stok (jika kolom stok ada)
            if (Schema::hasColumn('tiket', 'stok')) {
                if ((int)$t->stok < (int)$data['jumlah']) {
                    abort(422, 'Stok tiket tidak mencukupi.');
                }
                $t->decrement('stok', (int)$data['jumlah']);
            }

            // upload bukti → disimpan ke storage/public/bukti
            $path = $request->file('bukti')->store('bukti', 'public');

            PembelianTiket::create([
                'kode'          => 'TCK-'.now()->format('Ymd').'-'.Str::upper(Str::random(6)),
                'qr_token'      => Str::random(40),
                'pengunjung_id' => $pengunjung->id,
                'tiket_id'      => $t->id,
                'jumlah'        => (int)$data['jumlah'],
                'total_harga'   => (int)($t->harga ?? 0) * (int)$data['jumlah'],
                'qr_code'       => $path,     // bukti pembayaran (sesuai setup kamu)
                'status_bayar'  => 'pending',
            ]);
        });

        return redirect()->route('pembelian-tiket.index')
            ->with('success', 'Pembelian tersimpan. Menunggu verifikasi admin.');
    }

    /**
     * Detail (opsional) → hanya untuk pemilik
     */
    public function show($id, Request $request)
    {
        $emailLogin = optional($request->user())->email;
        $pengunjung = $emailLogin ? Pengunjung::where('email', $emailLogin)->first() : null;

        $row = PembelianTiket::with(['tiket','pengunjung'])->findOrFail($id);
        if (!$pengunjung || (int)$row->pengunjung_id !== (int)$pengunjung->id) {
            abort(403, 'Akses ditolak.');
        }

        return view('pembelian_tiket', ['pembelian' => $row]);
    }

    /**
     * QR dinamis (SVG default, PNG opsional via ?fmt=png)
     * Hanya untuk pemilik & status_bayar = 'lunas'
     */
    public function qr($id, Request $request)
    {
        try {
            $emailLogin = optional($request->user())->email;
            $pengunjung = $emailLogin ? Pengunjung::where('email', $emailLogin)->first() : null;

            $row = PembelianTiket::findOrFail($id);

            // kepemilikan
            if (!$pengunjung || (int)$row->pengunjung_id !== (int)$pengunjung->id) {
                abort(403, 'Akses ditolak.');
            }

            // hanya setelah lunas
            if (($row->status_bayar ?? '') !== 'lunas') {
                abort(403, 'QR hanya tersedia setelah pembayaran disetujui.');
            }

            // payload + checksum
            $payload = json_encode([
                'kode'          => $row->kode,
                'pengunjung_id' => (int)$row->pengunjung_id,
                'tiket_id'      => (int)$row->tiket_id,
                'jumlah'        => (int)$row->jumlah,
                'ts'            => now()->timestamp,
            ], JSON_UNESCAPED_SLASHES);

            $secret   = config('app.key') ?: 'fallback-secret';
            $checksum = hash_hmac('sha256', $payload, $secret);
            $display  = "TCK|{$row->kode}|UID:{$row->pengunjung_id}|TID:{$row->tiket_id}|SIG:{$checksum}";

            $fmt = strtolower((string)$request->query('fmt', 'svg'));

            if ($fmt === 'png') {
                $png = QrCode::format('png')->size(320)->margin(1)->generate($display);
                return Response::make($png, 200, [
                    'Content-Type'        => 'image/png',
                    'Content-Disposition' => 'inline; filename="qr-'.$row->kode.'.png"',
                    'Cache-Control'       => 'no-store, no-cache, must-revalidate, max-age=0',
                ]);
            }

            // default: SVG
            $svg = QrCode::format('svg')->size(320)->margin(1)->generate($display);
            return Response::make($svg, 200, [
                'Content-Type'        => 'image/svg+xml',
                'Content-Disposition' => 'inline; filename="qr-'.$row->kode.'.svg"',
                'Cache-Control'       => 'no-store, no-cache, must-revalidate, max-age=0',
            ]);
        } catch (\Throwable $e) {
            \Log::error('QR render error: '.$e->getMessage(), ['id' => $id]);
            abort(404);
        }
    }
}
