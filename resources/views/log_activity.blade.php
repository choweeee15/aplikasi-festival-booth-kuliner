@extends('layouts.app')

@section('title', 'Log Aktivitas')

@section('dashboard-css')
<style>
    /* Container styling mirip dashboard */
    .log-activity-container {
        padding: 30px;
        background: linear-gradient(135deg, #4e0000, #800000);
        min-height: 100vh;
        color: #f8f8f8;
        animation: fadeInScale 1s ease forwards;
    }

    @keyframes fadeInScale {
        0% { opacity: 0; transform: scale(0.95); }
        100% { opacity: 1; transform: scale(1); }
    }

    /* Tabel styling */
    .table {
        background: #fff;
        color: #000;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 8px 20px rgb(255 0 0 / 0.2);
    }

    .table thead th {
        background: linear-gradient(45deg, #7b0000, #b30000);
        color: white;
        font-weight: 700;
        border: none;
    }

    .table tbody tr:hover {
        background: #ffe6e6;
    }

    .table td,
    .table th {
        vertical-align: middle;
        font-family: 'Montserrat', sans-serif;
    }

    /* Button styles */
    .btn-primary {
        background: linear-gradient(45deg, #b30000, #7b0000);
        color: #fff;
        border: none;
        font-weight: 600;
        box-shadow: 0 4px 10px rgba(179,0,0,0.6);
        transition: background 0.3s ease;
    }

    .btn-primary:hover {
        background: linear-gradient(45deg, #d10000, #a30000);
        box-shadow: 0 6px 15px rgba(209,0,0,0.75);
    }

    /* Pagination styles */
    .pagination .page-link {
        color: #000;
    }

    .pagination .page-item.active .page-link {
        background-color: #b30000;
        border-color: #7b0000;
    }

    /* Search bar styles */
    .search-bar {
        margin-bottom: 20px;
        display: flex;
        justify-content: flex-start;
        gap: 10px;
    }

    .search-bar input, .search-bar select {
        padding: 10px;
        font-size: 1rem;
        border-radius: 5px;
        border: 1px solid #ced4da;
    }

    .search-bar select {
        width: 200px;
    }

</style>
@endsection

@section('content')
<div class="log-activity-container">
    <div class="d-flex justify-content-between align-items-center mb-4" data-aos="fade-down" data-aos-duration="700">
        <h2 class="title-heading"><i class="fas fa-history me-2"></i> Log Aktivitas</h2>
    </div>

    <!-- Search Bar and Filter -->
    <div class="search-bar">
        <input type="text" id="searchInput" placeholder="Cari log aktivitas..." onkeyup="searchTable()">
        <select id="filterColumn" onchange="searchTable()">
            <option value="all">Semua Kolom</option>
            <option value="username">Username</option>
            <option value="activity">Aktivitas</option>
            <option value="ip_address">IP Address</option>
            <option value="created_at">Waktu</option>
        </select>
    </div>

    <div class="card card-dark" data-aos="fade-up" data-aos-duration="700" data-aos-delay="200">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-dark table-striped align-middle text-center mb-0" id="logTable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Aktivitas</th>
                            <th>IP Address</th>
                            <th>Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($logs as $log)
                        <tr>
                            <td>{{ $log->id }}</td>
                            <td>{{ $log->username }}</td>
                            <td>{{ $log->activity }}</td>
                            <td>{{ $log->ip_address }}</td>
                            <td>{{ $log->created_at->format('d-m-Y H:i:s') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">Tidak ada data log aktivitas.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Pagination Manual --}}
    <div class="d-flex justify-content-center gap-2 mt-3">
        {{-- Previous --}}
        @if ($logs->currentPage() > 1)
            <a href="{{ request()->fullUrlWithQuery(['page' => $logs->currentPage() - 1]) }}" class="btn btn-primary">Previous</a>
        @else
            <button class="btn btn-secondary" disabled>Previous</button>
        @endif

        {{-- Next --}}
        @if ($logs->currentPage() < $logs->lastPage())
            <a href="{{ request()->fullUrlWithQuery(['page' => $logs->currentPage() + 1]) }}" class="btn btn-primary">Next</a>
        @else
            <button class="btn btn-secondary" disabled>Next</button>
        @endif
    </div>
</div>

<script>
    function searchTable() {
        var input, filter, table, tr, td, i, txtValue, column;
        input = document.getElementById("searchInput");
        filter = input.value.toLowerCase();
        table = document.getElementById("logTable");
        tr = table.getElementsByTagName("tr");
        column = document.getElementById("filterColumn").value;

        // Loop through all table rows (except the first, which is the header)
        for (i = 1; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td");
            var found = false;

            // Find the column index based on selected filter
            var columnIndex = 0;
            if (column === 'username') columnIndex = 1;
            else if (column === 'activity') columnIndex = 2;
            else if (column === 'ip_address') columnIndex = 3;
            else if (column === 'created_at') columnIndex = 4;
            else if (column === 'all') columnIndex = -1;  // Search in all columns

            if (columnIndex === -1) {
                // Loop through all columns if searching in all columns
                for (var j = 0; j < td.length; j++) {
                    txtValue = td[j].textContent || td[j].innerText;
                    if (txtValue.toLowerCase().indexOf(filter) > -1) {
                        found = true;
                        break;
                    }
                }
            } else {
                // Only search in the selected column
                if (td[columnIndex]) {
                    txtValue = td[columnIndex].textContent || td[columnIndex].innerText;
                    if (txtValue.toLowerCase().indexOf(filter) > -1) {
                        found = true;
                    }
                }
            }

            // If any match is found, display the row, else hide it
            if (found) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }
</script>

@endsection
