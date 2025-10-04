<?php

namespace App\Http\Controllers;

use App\Models\Pengunjung;
use Illuminate\Http\Request;

class PengunjungController extends Controller
{
    public function index(Request $request)
    {
        $q = trim($request->get('q',''));
        $perPage = (int) $request->get('per_page', 10);

        $pengunjungs = Pengunjung::query()
            ->when($q !== '', function($qr) use ($q){
                $qr->where('nama','like',"%$q%")
                   ->orWhere('email','like',"%$q%")
                   ->orWhere('no_hp','like',"%$q%");
            })
            ->orderByDesc('id')
            ->paginate($perPage)
            ->withQueryString();

        return view('admin_pengunjungs_index', compact('pengunjungs','q','perPage'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama'  => ['required','string','max:150'],
            'email' => ['required','email','max:150'],
            'no_hp' => ['nullable','string','max:50'],
        ]);

        Pengunjung::create($data);
        return back()->with('success','Pengunjung berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $row = Pengunjung::findOrFail($id);

        $data = $request->validate([
            'nama'  => ['required','string','max:150'],
            'email' => ['required','email','max:150'],
            'no_hp' => ['nullable','string','max:50'],
        ]);

        $row->update($data);
        return back()->with('success','Pengunjung berhasil diperbarui.');
    }

    public function destroy($id)
    {
        Pengunjung::findOrFail($id)->delete();
        return back()->with('success','Pengunjung berhasil dihapus.');
    }
}
