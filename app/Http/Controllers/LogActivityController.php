<?php

namespace App\Http\Controllers;

use App\Models\LogActivity;
use Illuminate\Http\Request;

class LogActivityController extends Controller
{
    public function index()
    {
        // Ambil data log terbaru, paginate 10 per halaman
        $logs = LogActivity::orderBy('created_at', 'desc')->paginate(10);

        // Kirim ke view
        return view('log_activity', compact('logs'));
    }
}