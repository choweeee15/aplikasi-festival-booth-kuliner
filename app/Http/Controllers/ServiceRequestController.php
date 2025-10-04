<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Registrasi;
use App\Models\Layanan;
use App\Models\Pembayaran;
use App\Models\LogActivity;  // Pastikan LogActivity model diimport
use Illuminate\Support\Facades\Storage;

class ServiceRequestController extends Controller
{
    public function index()
    {
        $requests = Registrasi::with(['layanan', 'pembayaran'])->orderBy('created_at', 'desc')->get();
        $layanans = Layanan::where('status', 'aktif')->get();

        // Log aktivitas: Admin melihat permintaan layanan
        LogActivity::create([
            'username' => auth()->user()->email ?? 'Guest',
            'activity' => "Admin melihat permintaan layanan.",
            'ip_address' => request()->ip(),
        ]);

        return view('servicerequest', compact('requests', 'layanans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pemilik' => 'required|string',
            'layanan_id' => 'required|exists:layanan,id',
            'tipe_kendaraan' => 'nullable|string',
            'bukti_pembayaran' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048'
        ]);

        $registrasi = Registrasi::create([
            'nama_pemilik' => $request->nama_pemilik,
            'layanan_id' => $request->layanan_id,
            'tipe_kendaraan' => $request->tipe_kendaraan,
            'status' => 'menunggu',
        ]);

        // Log aktivitas: Admin atau pengguna melakukan permintaan layanan baru
        LogActivity::create([
            'username' => auth()->user()->email ?? 'Guest',
            'activity' => "Mengajukan permintaan layanan baru untuk kendaraan: {$request->tipe_kendaraan}",
            'ip_address' => $request->ip(),
        ]);

        // Jika pengguna upload bukti pembayaran saat booking (opsional)
        if ($request->hasFile('bukti_pembayaran')) {
            $path = $request->file('bukti_pembayaran')->store('bukti_pembayaran', 'public');

            Pembayaran::create([
                'permintaan_id' => $registrasi->id,
                'bukti_pembayaran' => $path,
                'status' => 'menunggu',
                'tanggal_upload' => now(),
            ]);

            // Log aktivitas: Admin mengupload bukti pembayaran
            LogActivity::create([
                'username' => auth()->user()->email ?? 'Guest',
                'activity' => "Mengupload bukti pembayaran untuk permintaan ID: {$registrasi->id}",
                'ip_address' => $request->ip(),
            ]);
        }

        return redirect()->route('service.requests')->with('success', 'Permintaan berhasil ditambahkan!');
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:menunggu,dikonfirmasi,diproses,selesai,ditolak',
            'jumlah_tagihan' => 'nullable|integer|min:0',
            'rincian_biaya' => 'nullable|string|max:1000',
            'catatan_penolakan' => 'nullable|string|max:500',
        ]);

        $registrasi = Registrasi::findOrFail($id);
        $registrasi->status = $request->status;
        $registrasi->save();

        // Log aktivitas: Admin memperbarui status permintaan layanan
        LogActivity::create([
            'username' => auth()->user()->email ?? 'Guest',
            'activity' => "Memperbarui status permintaan layanan ID: {$registrasi->id} menjadi: {$registrasi->status}",
            'ip_address' => $request->ip(),
        ]);

        // Handle dikonfirmasi
        if ($request->status === 'dikonfirmasi') {
            $pembayaran = Pembayaran::firstOrCreate(
                ['permintaan_id' => $registrasi->id],
                ['status' => 'menunggu']
            );
            $pembayaran->jumlah_tagihan = $request->jumlah_tagihan;
            $pembayaran->rincian_biaya = $request->rincian_biaya;
            $pembayaran->save();

            // Log aktivitas: Admin mengkonfirmasi permintaan layanan
            LogActivity::create([
                'username' => auth()->user()->email ?? 'Guest',
                'activity' => "Mengonfirmasi permintaan layanan ID: {$registrasi->id} dan menambahkan tagihan sebesar: {$request->jumlah_tagihan}",
                'ip_address' => $request->ip(),
            ]);
        }

        // Handle ditolak
        if ($request->status === 'ditolak') {
            $pembayaran = Pembayaran::where('permintaan_id', $registrasi->id)->first();
            if ($pembayaran) {
                $pembayaran->status = 'ditolak';
                $pembayaran->catatan = $request->catatan_penolakan;
                $pembayaran->save();
            }

            // Log aktivitas: Admin menolak permintaan layanan
            LogActivity::create([
                'username' => auth()->user()->email ?? 'Guest',
                'activity' => "Menolak permintaan layanan ID: {$registrasi->id} dengan alasan: {$request->catatan_penolakan}",
                'ip_address' => $request->ip(),
            ]);
        }

        return redirect()->route('service.requests')->with('success', 'Status berhasil diperbarui.');
    }
}
