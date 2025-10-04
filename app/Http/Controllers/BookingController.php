<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Registrasi;
use App\Models\LogActivity;  // Pastikan model LogActivity diimport
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nama_pemilik' => 'required|string',
            'email' => 'nullable|email',
            'no_hp' => 'nullable|string',
            'kategori_id' => 'required|exists:kategori_kendaraan,id',
            'layanan_id' => 'required|exists:layanan,id',
            'nama_kendaraan' => 'nullable|string',
            'model_kendaraan' => 'nullable|string',
            'tipe_kendaraan' => 'nullable|string',
            'jenis_permintaan' => 'nullable|in:dropoff,pickup',
            'alamat' => 'nullable|string',
            'catatan_admin' => 'nullable|string',
        ]);

        // Simpan data booking ke Registrasi
        $booking = Registrasi::create([
            'user_id' => Auth::id(),
            'kategori_id' => $request->kategori_id,
            'layanan_id' => $request->layanan_id,
            'nama_pemilik' => $request->nama_pemilik,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'nama_kendaraan' => $request->nama_kendaraan,
            'model_kendaraan' => $request->model_kendaraan,
            'tipe_kendaraan' => $request->tipe_kendaraan,
            'jenis_permintaan' => $request->jenis_permintaan,
            'alamat' => $request->alamat,
            'catatan_admin' => $request->catatan_admin,
            'status' => 'menunggu',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Log aktivitas: Booking berhasil
        LogActivity::create([
            'username' => Auth::user()->email ?? 'Guest',
            'activity' => "Melakukan booking untuk kendaraan: {$request->nama_kendaraan} ({$request->tipe_kendaraan})",
            'ip_address' => $request->ip(),
        ]);

        // Tampilkan pesan sukses
        return back()->with('success', 'Booking berhasil dikirim. Tunggu konfirmasi dari admin.');
    }

    public function history()
    {
        $userId = Auth::id();

        // Ambil data booking yang sudah dilakukan oleh user
        $bookings = Registrasi::with('layanan')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        // Log aktivitas: Melihat history booking
        LogActivity::create([
            'username' => Auth::user()->email ?? 'Guest',
            'activity' => "Melihat riwayat booking",
            'ip_address' => request()->ip(),
        ]);

        return view('booking_history', compact('bookings'));
    }
}
