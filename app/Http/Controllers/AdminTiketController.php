<?php

namespace App\Http\Controllers;

use App\Models\Tiket;
use Illuminate\Http\Request;

class AdminTiketController extends Controller
{
    public function index(Request $r)
    {
        $q = Tiket::query();
        if ($s = $r->query('s')) {
            $q->where('nama_tiket','like',"%$s%");
        }
        $rows = $q->orderBy('harga')->paginate(10)->withQueryString();

        $rekap = [
            'jenis' => Tiket::count(),
            'stok_total' => Tiket::sum('stok'),
            'harga_min'  => Tiket::min('harga'),
            'harga_max'  => Tiket::max('harga'),
        ];
        return view('admin_tiket_index', compact('rows','rekap','s'));
    }
}
