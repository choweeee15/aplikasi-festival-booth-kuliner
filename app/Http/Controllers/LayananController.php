<?php

namespace App\Http\Controllers;

use App\Models\Layanan;
use App\Models\LogActivity;  // Pastikan LogActivity model diimport
use Illuminate\Http\Request;

class LayananController extends Controller
{
    public function index()
    {
        $layanans = Layanan::all();
        return view('service', compact('layanans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_layanan' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'estimasi' => 'required|numeric|min:1',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        // Menyimpan layanan baru
        $layanan = Layanan::create([
            'nama_layanan' => $request->nama_layanan,
            'harga' => $request->harga,
            'estimasi' => $request->estimasi,
            'status' => $request->status,
        ]);

        // Log aktivitas: Admin menambahkan layanan baru
        LogActivity::create([
            'username' => auth()->user()->email ?? 'Guest',  // Mengambil email admin
            'activity' => "Menambahkan layanan baru dengan nama: {$layanan->nama_layanan}",
            'ip_address' => $request->ip(),  // Alamat IP pengguna
        ]);

        return redirect()->route('layanan.index')->with('success', 'Layanan berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_layanan' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'estimasi' => 'required|numeric|min:1',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        // Menemukan layanan yang akan diperbarui
        $layanan = Layanan::findOrFail($id);
        $layanan->update([
            'nama_layanan' => $request->nama_layanan,
            'harga' => $request->harga,
            'estimasi' => $request->estimasi,
            'status' => $request->status,
        ]);

        // Log aktivitas: Admin memperbarui layanan
        LogActivity::create([
            'username' => auth()->user()->email ?? 'Guest',
            'activity' => "Memperbarui layanan dengan nama: {$layanan->nama_layanan}",
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('layanan.index')->with('success', 'Layanan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        // Menemukan layanan yang akan dihapus
        $layanan = Layanan::findOrFail($id);
        $layanan->delete();

        // Log aktivitas: Admin menghapus layanan
        LogActivity::create([
            'username' => auth()->user()->email ?? 'Guest',
            'activity' => "Menghapus layanan dengan nama: {$layanan->nama_layanan}",
            'ip_address' => request()->ip(),
        ]);

        return redirect()->route('layanan.index')->with('success', 'Layanan berhasil dihapus.');
    }
}
