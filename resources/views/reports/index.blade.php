@extends('layouts.app')

@section('title', 'Service Requests Report')

@section('content')
<div class="container py-4">

    <h2 class="mb-4">Service Requests Report</h2>

    <form method="GET" action="{{ route('reports.index') }}" class="row g-3 mb-4">
        <div class="col-md-3">
            <label for="start_date" class="form-label">Start Date</label>
            <input type="date" id="start_date" name="start_date" value="{{ request('start_date') }}" class="form-control">
        </div>

        <div class="col-md-3">
            <label for="end_date" class="form-label">End Date</label>
            <input type="date" id="end_date" name="end_date" value="{{ request('end_date') }}" class="form-control">
        </div>

        <div class="col-md-3">
            <label for="status" class="form-label">Status</label>
            <select name="status" id="status" class="form-select">
                <option value="">-- All Status --</option>
                @foreach ($statuses as $status)
                    <option value="{{ $status }}" @if(request('status') == $status) selected @endif>{{ ucfirst(str_replace('-', ' ', $status)) }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3 d-flex align-items-end">
            <button type="submit" class="btn btn-primary me-2">Filter</button>
            <a href="{{ route('reports.export', request()->query()) }}" class="btn btn-success">Export Excel</a>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Date Time</th>
                    <th>Owner Name</th>
                    <th>Vehicle Name</th>
                    <th>Vehicle Reg. No</th>
                    <th>Assigned To</th>
                    <th>Service</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($serviceRequests as $req)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($req->date_time)->format('d M Y H:i') }}</td>
                        <td>{{ $req->owner_name }}</td>
                        <td>{{ $req->vehicle_name }}</td>
                        <td>{{ $req->vehicle_reg_no }}</td>
                        <td>{{ $req->mekanik ? $req->mekanik->nama : '-' }}</td>
                        <td>{{ $req->service }}</td>
                        <td>
                            <span class="badge 
                                @if($req->status == 'pending') bg-warning
                                @elseif($req->status == 'confirmed') bg-info
                                @elseif($req->status == 'on-progress') bg-primary
                                @elseif($req->status == 'done') bg-success
                                @elseif($req->status == 'cancelled') bg-danger
                                @endif
                            ">{{ ucfirst(str_replace('-', ' ', $req->status)) }}</span>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center">No records found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div>
        {{ $serviceRequests->withQueryString()->links() }}
    </div>

</div>
@endsection
