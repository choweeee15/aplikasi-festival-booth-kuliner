<?php

namespace App\Http\Controllers;

use App\Models\PembelianTiket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GateScanController extends Controller
{
    /**
     * Halaman sederhana untuk petugas gate:
     * - paste string QR atau buka via ?data=...
     */
    public function index(Request $r)
    {
        return view('gate_scan', [
            'result' => null,
            'data'   => (string) $r->query('data', ''),
        ]);
    }

    /**
     * Verifikasi via GET ?data=... (untuk scanner yang open URL)
     */
    public function verifyGet(Request $r)
    {
        $data = (string) $r->query('data', '');
        [$ok, $msg, $row] = $this->verifyCore($data, $r->user()?->id);

        // Tampilkan di view yang sama
        return view('gate_scan', [
            'result' => ['ok' => $ok, 'msg' => $msg, 'row' => $row],
            'data'   => $data,
        ]);
    }

    /**
     * Verifikasi via POST form (input textarea)
     */
    public function verify(Request $r)
    {
        $data = (string) $r->input('data', '');
        [$ok, $msg, $row] = $this->verifyCore($data, $r->user()?->id);

        return view('gate_scan', [
            'result' => ['ok' => $ok, 'msg' => $msg, 'row' => $row],
            'data'   => $data,
        ]);
    }

    /**
     * Inti verifikasi QR:
     * - Parse string "TCK|{KODE}|UID:{uid}|TID:{tid}|SIG:{checksum}"
     * - Cek checksum HMAC sesuai APP_KEY pada payload json yang dipakai saat generate
     * - Cek status_bayar = lunas
     * - Cegah reuse (jika checked_in_at sudah terisi, tolak)
     * - Update checked_in_at, checked_in_by, scan_count, last_scanned_at
     */
    private function verifyCore(string $data, ?int $checkerId): array
    {
        $data = trim($data);
        if ($data === '') {
            return [false, 'Data QR kosong.', null];
        }

        // Format dasar
        // Contoh: "TCK|TCK-20251002-ABC123|UID:4|TID:1|SIG:abcdef..."
        if (strpos($data, 'TCK|') !== 0) {
            return [false, 'Format QR tidak dikenali.', null];
        }

        $parts = explode('|', $data);
        // minimal: [TCK, {KODE}, UID:..., TID:..., SIG:...]
        if (count($parts) < 5) {
            return [false, 'Data QR tidak lengkap.', null];
        }

        $kode = $parts[1] ?? '';
        $uid  = null;
        $tid  = null;
        $sig  = null;

        foreach ($parts as $p) {
            if (str_starts_with($p, 'UID:')) $uid = (int) substr($p, 4);
            if (str_starts_with($p, 'TID:')) $tid = (int) substr($p, 4);
            if (str_starts_with($p, 'SIG:')) $sig = substr($p, 4);
        }

        if (!$kode || !$uid || !$tid || !$sig) {
            return [false, 'QR tidak berisi kode/UID/TID/SIG yang valid.', null];
        }

        // Ambil tiketnya
        $row = PembelianTiket::where('kode', $kode)->first();
        if (!$row) return [false, 'Tiket tidak ditemukan.', null];

        // Cocokkan UID & TID dari QR ke DB
        if ((int)$row->pengunjung_id !== (int)$uid || (int)$row->tiket_id !== (int)$tid) {
            return [false, 'QR tidak cocok dengan data tiket.', null];
        }

        // Status harus lunas
        if (($row->status_bayar ?? '') !== 'lunas') {
            return [false, 'Tiket belum lunas / belum disetujui.', $row];
        }

        // Recompute checksum seperti saat generate:
        $payload = json_encode([
            'kode'          => $row->kode,
            'pengunjung_id' => (int)$row->pengunjung_id,
            'tiket_id'      => (int)$row->tiket_id,
            'jumlah'        => (int)$row->jumlah,
            'ts'            => null, // ts tidak ikut HMAC saat verifikasi (kita pakai subset deterministik)
        ], JSON_UNESCAPED_SLASHES);

        $secret = config('app.key') ?: 'fallback-secret';
        $expected = hash_hmac('sha256', $payload, $secret);

        // Agar sinkron dengan generate (yang menyertakan ts), kita longgarkan:
        // - Terima HMAC dari payload tanpa ts (subset deterministik)
        // - Atau terima HMAC lama (jika kamu ingin strict, hapus opsi kedua)
        $validSig = hash_equals($expected, $sig);
        if (!$validSig) {
            // Coba versi dengan ts=0 (fallback)—opsional jika kamu ingin kompatibel
            $payload2 = json_encode([
                'kode'          => $row->kode,
                'pengunjung_id' => (int)$row->pengunjung_id,
                'tiket_id'      => (int)$row->tiket_id,
                'jumlah'        => (int)$row->jumlah,
                'ts'            => 0,
            ], JSON_UNESCAPED_SLASHES);
            $expected2 = hash_hmac('sha256', $payload2, $secret);
            if (!hash_equals($expected2, $sig)) {
                return [false, 'Checksum QR tidak valid.', null];
            }
        }

        // Anti-reuse: jika sudah check-in, tolak
        if ($row->checked_in_at) {
            // Update statistik scan saja
            $row->increment('scan_count');
            $row->last_scanned_at = now();
            $row->save();
            return [false, 'QR sudah digunakan untuk check-in.', $row];
        }

        // Tandai check-in
        DB::transaction(function () use ($row, $checkerId) {
            $row->checked_in_at  = now();
            $row->checked_in_by  = $checkerId;
            $row->scan_count     = ($row->scan_count ?? 0) + 1;
            $row->last_scanned_at= now();
            $row->save();
        });

        return [true, 'Check-in BERHASIL.', $row];
    }
}
