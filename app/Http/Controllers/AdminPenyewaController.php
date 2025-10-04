<?php

namespace App\Http\Controllers;

use App\Models\Penyewa;
use Illuminate\Http\Request;

class AdminPenyewaController extends Controller
{
    public function index(Request $r)
    {
        $q = Penyewa::query();
        if ($s = $r->query('s')) {
            $q->where(function($x) use ($s){
                $x->where('nama','like',"%$s%")
                  ->orWhere('email','like',"%$s%")
                  ->orWhere('no_hp','like',"%$s%");
            });
        }
        $rows = $q->orderByDesc('id')->paginate(10)->withQueryString();
        $rekap = ['total' => Penyewa::count()];
        return view('admin_penyewas_index', compact('rows','rekap','s'));
    }
}
