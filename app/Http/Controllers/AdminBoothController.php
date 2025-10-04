<?php

namespace App\Http\Controllers;

use App\Models\Booth;
use Illuminate\Http\Request;

class AdminBoothController extends Controller
{
    public function index(Request $r)
    {
        $q = Booth::query();

        if ($s = $r->query('s')) {
            $q->where(function($x) use ($s){
                $x->where('nama_booth','like',"%$s%")
                  ->orWhere('deskripsi','like',"%$s%");
            });
        }
        if ($status = $r->query('status')) {
            $q->where('status',$status);
        }

        $rows = $q->orderByDesc('id')->paginate(10)->withQueryString();
        $rekap = [
            'total' => Booth::count(),
            'tersedia' => Booth::where('status','tersedia')->count(),
            'tersewa'  => Booth::where('status','tersewa')->count(),
        ];

        return view('admin_booths_index', compact('rows','rekap','s','status'));
    }
}
