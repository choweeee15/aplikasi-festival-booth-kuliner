<?php

namespace App\Http\Controllers;

use App\Models\TransaksiSewa;
use App\Models\Penyewa;
use App\Models\Booth;
use Illuminate\Http\Request;

class TransaksiSewaController extends Controller
{
    public function index(Request $request)
    {
        $q = trim($request->get('q',''));
        $perPage = (int) $request->get('per_page', 10);

        $sewa = TransaksiSewa::with(['penyewa','booth'])
            ->when($q !== '', function($qr) use ($q){
                $qr->whereHas('penyewa', function($w) use ($q){
                        $w->where('nama','like',"%$q%")->orWhere('email','like',"%$q%");
                    })
                   ->orWhereHas('booth', function($w) use ($q){
                        $w->where('nama_booth','like',"%$q%");
                    })
                   ->orWhere('status','like',"%$q%");
            })
            ->orderByDesc('id')
            ->paginate($perPage)
            ->withQueryString();

        $penyewaList = Penyewa::orderBy('nama')->get();
        $boothList   = Booth::orderBy('nama_booth')->get();

        return view('admin_sewa_index', compact('sewa','penyewaList','boothList','q','perPage'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'penyewa_id'      => ['required','exists:penyewas,id'],
            'booth_id'        => ['required','exists:booths,id'],
            'tanggal_mulai'   => ['required','date'],
            'tanggal_selesai' => ['required','date','after_or_equal:tanggal_mulai'],
            'total_bayar'     => ['required','numeric','min:0','max:9999999999'],
            'status'          => ['required','in:menunggu,disetujui,ditolak'],
        ]);

        TransaksiSewa::create($data);
        return back()->with('success','Transaksi sewa berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $row = TransaksiSewa::findOrFail($id);

        $data = $request->validate([
            'penyewa_id'      => ['required','exists:penyewas,id'],
            'booth_id'        => ['required','exists:booths,id'],
            'tanggal_mulai'   => ['required','date'],
            'tanggal_selesai' => ['required','date','after_or_equal:tanggal_mulai'],
            'total_bayar'     => ['required','numeric','min:0','max:9999999999'],
            'status'          => ['required','in:menunggu,disetujui,ditolak'],
        ]);

        $row->update($data);
        return back()->with('success','Transaksi sewa berhasil diperbarui.');
    }

    public function destroy($id)
    {
        TransaksiSewa::findOrFail($id)->delete();
        return back()->with('success','Transaksi sewa berhasil dihapus.');
    }
}
