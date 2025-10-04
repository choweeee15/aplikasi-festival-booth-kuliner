<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Registrasi;
use App\Models\LogActivity;  // Pastikan LogActivity model diimport
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    public function exportExcel(Request $request)
    {
        $user = auth()->user();

        // Query dasar untuk mengambil pembayaran yang terkait dengan user
        $query = Pembayaran::with('registrasi.layanan')
            ->whereHas('registrasi', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            });

        // Filter berdasarkan tanggal jika ada
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('tanggal_upload', [
                $request->input('start_date'),
                $request->input('end_date')
            ]);
        }

        // Ambil data pembayaran
        $payments = $query->latest()->get();

        // Log aktivitas: Admin mengunduh pembayaran dalam format Excel
        LogActivity::create([
            'username' => $user->email ?? 'Guest',
            'activity' => "Mengunduh riwayat pembayaran dalam format Excel.",
            'ip_address' => $request->ip(),
        ]);

        // Export to Excel
        return Excel::download(new PembayaranExport($payments), 'riwayat_pembayaran.xlsx');
    }

    public function exportPdf(Request $request)
    {
        $user = auth()->user();

        // Query dasar untuk mengambil pembayaran yang terkait dengan user
        $query = Pembayaran::with('registrasi.layanan')
            ->whereHas('registrasi', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            });

        // Filter berdasarkan tanggal jika ada
        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('tanggal_upload', [
                $request->input('start_date'),
                $request->input('end_date')
            ]);
        }

        // Ambil data pembayaran
        $payments = $query->latest()->get();

        // Log aktivitas: Admin mengunduh pembayaran dalam format PDF
        LogActivity::create([
            'username' => $user->email ?? 'Guest',
            'activity' => "Mengunduh riwayat pembayaran dalam format PDF.",
            'ip_address' => $request->ip(),
        ]);

        // Export to PDF
        $pdf = PDF::loadView('exports.pembayaran-pdf', compact('payments'));
        return $pdf->download('riwayat_pembayaran.pdf');
    }

    public function index()
    {
        $user = auth()->user();

        // Fetch payments related to the authenticated user's registrations
        $payments = Pembayaran::with('registrasi.layanan')
            ->whereHas('registrasi', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->latest()
            ->paginate(10);

        // Log aktivitas: Pengguna melihat riwayat pembayaran mereka
        LogActivity::create([
            'username' => $user->email ?? 'Guest',
            'activity' => "Melihat riwayat pembayaran.",
            'ip_address' => request()->ip(),
        ]);

        return view('riwayat-pembayaran', compact('payments'));
    }

    public function indexadmin(Request $request)
    {
        // Ambil query pembayaran beserta relasi registrasi dan layanan
        $query = Pembayaran::with('registrasi.layanan');

        // Filter rentang tanggal jika ada
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal_upload', [
                $request->input('start_date'),
                $request->input('end_date')
            ]);
        }

        // Ambil data pembayaran dengan pagination 10 per halaman, urut terbaru dulu
        $payments = $query->latest()->paginate(10);

        // Log aktivitas: Admin melihat riwayat pembayaran
        LogActivity::create([
            'username' => auth()->user()->email ?? 'Guest',
            'activity' => "Admin melihat riwayat pembayaran.",
            'ip_address' => request()->ip(),
        ]);

        return view('riwayat-pembayaran-admin', compact('payments'));
    }

    public function upload(Request $request, $id)
    {
        $request->validate([
            'bukti_pembayaran' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048'
        ]);

        $registrasi = Registrasi::findOrFail($id);

        // Store the payment proof
        $path = $request->file('bukti_pembayaran')->store('bukti_pembayaran', 'public');

        // Update or create the payment entry
        Pembayaran::updateOrCreate(
            ['permintaan_id' => $registrasi->id],
            [
                'bukti_pembayaran' => $path,
                'status' => 'menunggu',
                'tanggal_upload' => now()
            ]
        );

        // Log aktivitas: Admin mengupload bukti pembayaran
        LogActivity::create([
            'username' => auth()->user()->email ?? 'Guest',
            'activity' => "Admin mengupload bukti pembayaran untuk registrasi ID: {$registrasi->id}",
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'Bukti pembayaran berhasil diupload.');
    }

    public function verifikasi(Request $request, $id)
    {
        $pembayaran = Pembayaran::with('registrasi')->findOrFail($id);
        $aksi = $request->input('aksi');

        if ($aksi === 'verifikasi') {
            if ($pembayaran->status !== 'menunggu') {
                return back()->with('error', 'Pembayaran sudah diverifikasi.');
            }

            $pembayaran->status = 'diverifikasi';
            $pembayaran->tanggal_verifikasi = now();
            $pembayaran->save();

            // Update the status of the registration
            $pembayaran->registrasi->update(['status' => 'diproses']);

            // Log aktivitas: Admin memverifikasi pembayaran
            LogActivity::create([
                'username' => auth()->user()->email ?? 'Guest',
                'activity' => "Admin memverifikasi pembayaran dengan ID: {$pembayaran->id}",
                'ip_address' => $request->ip(),
            ]);

            return back()->with('success', 'Pembayaran diverifikasi dan status diubah menjadi diproses.');
        }

        if ($aksi === 'tolak') {
            $request->validate([
                'catatan_penolakan' => 'required|string|max:500',
            ]);

            $pembayaran->status = 'ditolak';
            $pembayaran->catatan = $request->input('catatan_penolakan');
            $pembayaran->save();

            // Log aktivitas: Admin menolak pembayaran
            LogActivity::create([
                'username' => auth()->user()->email ?? 'Guest',
                'activity' => "Admin menolak pembayaran dengan ID: {$pembayaran->id}",
                'ip_address' => $request->ip(),
            ]);

            return back()->with('success', 'Pembayaran ditolak dengan alasan.');
        }

        return back()->with('error', 'Aksi tidak valid.');
    }
}
