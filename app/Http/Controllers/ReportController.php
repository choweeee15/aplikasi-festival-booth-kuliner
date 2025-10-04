<?php

namespace App\Http\Controllers;

use App\Models\ServiceRequest;
use App\Models\Mekanik;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ServiceRequestsExport;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $query = ServiceRequest::with('mekanik');

        // Filter by date range jika ada
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('date_time', [$request->start_date, $request->end_date]);
        }

        // Filter by status jika ada
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $serviceRequests = $query->paginate(10);

        return view('reports.index', [
            'serviceRequests' => $serviceRequests,
            'statuses' => ['pending', 'confirmed', 'on-progress', 'done', 'cancelled']
        ]);
    }

    // Export Excel
    public function export(Request $request)
    {
        return Excel::download(new ServiceRequestsExport($request), 'service_requests.xlsx');
    }
}
