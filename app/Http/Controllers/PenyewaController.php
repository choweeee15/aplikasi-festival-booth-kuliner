<?php

namespace App\Http\Controllers;

use App\Models\Penyewa;
use Illuminate\Http\Request;

class PenyewaController extends Controller
{
    public function index(Request $request)
    {
        $q = trim($request->get('q',''));
        $perPage = (int) $request->get('per_page', 10);

        $penyewas = Penyewa::query()
            ->when($q !== '', function($qr) use ($q){
                $qr->where('nama','like',"%$q%")
                   ->orWhere('email','like',"%$q%")
                   ->orWhere('no_hp','like',"%$q%");
            })
            ->orderByDesc('id')
            ->paginate($perPage)
            ->withQueryString();

        return view('admin_penyewas_index', compact('penyewas','q','perPage'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama'   => ['required','string','max:150'],
            'email'  => ['required','email','max:150'],
            'no_hp'  => ['nullable','string','max:50'],
            'alamat' => ['nullable','string'],
        ]);

        Penyewa::create($data);
        return back()->with('success','Penyewa berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $row = Penyewa::findOrFail($id);

        $data = $request->validate([
            'nama'   => ['required','string','max:150'],
            'email'  => ['required','email','max:150'],
            'no_hp'  => ['nullable','string','max:50'],
            'alamat' => ['nullable','string'],
        ]);

        $row->update($data);
        return back()->with('success','Penyewa berhasil diperbarui.');
    }

    public function destroy($id)
    {
        Penyewa::findOrFail($id)->delete();
        return back()->with('success','Penyewa berhasil dihapus.');
    }
}
