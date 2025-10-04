<?php

namespace App\Http\Controllers;

use App\Models\Booth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BoothController extends Controller
{
    public function index(Request $r)
    {
        $q = Booth::query();

        if ($term = $r->query('q')) {
            $q->where(function($x) use ($term){
                $x->where('nama_booth','like',"%$term%")
                  ->orWhere('deskripsi','like',"%$term%");
            });
        }

        $perPage = (int)($r->query('per_page') ?? 10);
        $booths = $q->orderByDesc('id')->paginate($perPage)->withQueryString();

        return view('admin_booths_index', compact('booths'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_booth' => 'required|string|max:255',
            'deskripsi'  => 'nullable|string',
            'harga_sewa' => 'required|numeric|min:0',
            'status'     => 'required|in:tersedia,tersewa',
            'image'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $path = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('booths', 'public');
        }

        Booth::create([
            'nama_booth' => $data['nama_booth'],
            'deskripsi'  => $data['deskripsi'] ?? null,
            'harga_sewa' => $data['harga_sewa'],
            'status'     => $data['status'],
            'image_path' => $path,
        ]);

        return back()->with('success','Booth berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $booth = Booth::findOrFail($id);

        $data = $request->validate([
            'nama_booth'   => 'required|string|max:255',
            'deskripsi'    => 'nullable|string',
            'harga_sewa'   => 'required|numeric|min:0',
            'status'       => 'required|in:tersedia,tersewa',
            'image'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'hapus_gambar' => 'nullable|boolean',
        ]);

        // Hapus gambar lama jika diminta
        if ($request->boolean('hapus_gambar') && $booth->image_path && !preg_match('~^https?://~i', $booth->image_path)) {
            Storage::disk('public')->delete($booth->image_path);
            $booth->image_path = null;
        }

        // Upload gambar baru (replace)
        if ($request->hasFile('image')) {
            if ($booth->image_path && !preg_match('~^https?://~i', $booth->image_path)) {
                Storage::disk('public')->delete($booth->image_path);
            }
            $booth->image_path = $request->file('image')->store('booths', 'public');
        }

        $booth->nama_booth = $data['nama_booth'];
        $booth->deskripsi  = $data['deskripsi'] ?? null;
        $booth->harga_sewa = $data['harga_sewa'];
        $booth->status     = $data['status'];
        $booth->save();

        return back()->with('success','Booth berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $booth = Booth::findOrFail($id);
        if ($booth->image_path && !preg_match('~^https?://~i', $booth->image_path)) {
            Storage::disk('public')->delete($booth->image_path);
        }
        $booth->delete();
        return back()->with('success','Booth berhasil dihapus.');
    }
}
