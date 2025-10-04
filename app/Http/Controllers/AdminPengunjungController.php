<?php

namespace App\Http\Controllers;

use App\Models\Pengunjung;
use Illuminate\Http\Request;

class AdminPengunjungController extends Controller
{
    public function index(Request $request)
    {
        $q = Pengunjung::query();

        if ($search = $request->query('s')) {
            $q->where(function($x) use ($search){
                $x->where('nama','like',"%$search%")
                  ->orWhere('email','like',"%$search%")
                  ->orWhere('no_hp','like',"%$search%");
            });
        }

        $rows  = $q->orderByDesc('id')->paginate(10)->withQueryString();
        $rekap = ['total' => Pengunjung::count()];

        return view('admin_pengunjungs_index', compact('rows','rekap','search'));
    }
}
