<?php

namespace App\Http\Controllers;

use App\Models\Tiket;
use Illuminate\Http\Request;

class TiketController extends Controller
{
    public function index(Request $request)
    {
        $q = trim($request->get('q',''));
        $perPage = (int) $request->get('per_page', 10);

        $tiket = Tiket::query()
            ->when($q !== '', fn($qr)=>$qr->where('nama_tiket','like',"%$q%"))
            ->orderBy('harga','asc')
            ->paginate($perPage)
            ->withQueryString();

        return view('admin_tiket_index', compact('tiket','q','perPage'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_tiket' => ['required','string','max:150'],
            'harga'      => ['required','numeric','min:0','max:9999999999'],
            'stok'       => ['required','integer','min:0','max:2147483647'],
        ]);

        Tiket::create($data);
        return back()->with('success','Tiket berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $row = Tiket::findOrFail($id);

        $data = $request->validate([
            'nama_tiket' => ['required','string','max:150'],
            'harga'      => ['required','numeric','min:0','max:9999999999'],
            'stok'       => ['required','integer','min:0','max:2147483647'],
        ]);

        $row->update($data);
        return back()->with('success','Tiket berhasil diperbarui.');
    }

    public function destroy($id)
    {
        Tiket::findOrFail($id)->delete();
        return back()->with('success','Tiket berhasil dihapus.');
    }
}
