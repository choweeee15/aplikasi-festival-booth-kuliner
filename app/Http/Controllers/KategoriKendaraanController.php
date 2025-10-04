<?php

namespace App\Http\Controllers;

use App\Models\KategoriKendaraan;
use App\Models\LogActivity;  // Pastikan LogActivity model diimport
use Illuminate\Http\Request;

class KategoriKendaraanController extends Controller
{
    public function index()
    {
        $kategoriList = KategoriKendaraan::all();
        return view('category', compact('kategoriList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $kategori = KategoriKendaraan::create([
            'nama_kategori' => $request->nama_kategori,
            'status' => $request->status,
        ]);

        // Log aktivitas: Admin menambahkan kategori kendaraan
        LogActivity::create([
            'username' => auth()->user()->email ?? 'Guest',
            'activity' => "Menambahkan kategori kendaraan baru dengan nama: {$kategori->nama_kategori}",
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('category.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kategori' => 'required|string|max:255',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $kategori = KategoriKendaraan::findOrFail($id);
        $kategori->update([
            'nama_kategori' => $request->nama_kategori,
            'status' => $request->status,
        ]);

        // Log aktivitas: Admin memperbarui kategori kendaraan
        LogActivity::create([
            'username' => auth()->user()->email ?? 'Guest',
            'activity' => "Memperbarui kategori kendaraan dengan nama: {$kategori->nama_kategori}",
            'ip_address' => $request->ip(),
        ]);

        return redirect()->route('category.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $kategori = KategoriKendaraan::findOrFail($id);
        $kategori->delete();

        // Log aktivitas: Admin menghapus kategori kendaraan
        LogActivity::create([
            'username' => auth()->user()->email ?? 'Guest',
            'activity' => "Menghapus kategori kendaraan dengan nama: {$kategori->nama_kategori}",
            'ip_address' => request()->ip(),
        ]);

        return redirect()->route('category.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
