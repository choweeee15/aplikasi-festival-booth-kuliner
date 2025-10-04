@extends('layouts.app')
@section('title','Kelola Transaksi Sewa Booth')

@section('content')
<div class="dashboard-container">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="title-heading"><i class="fas fa-handshake me-2"></i> Transaksi Sewa Booth</h2>
    <div class="summary d-flex gap-2">
      <div class="card p-2 px-3"><small>Total</small><div class="fw-bold">{{ $rekap['total'] ?? 0 }}</div></div>
      @foreach(($filterStatuses ?? []) as $st)
        <div class="card p-2 px-3">
          <small>{{ ucfirst($st) }}</small>
          <div class="fw-bold">{{ $rekap[$st] ?? 0 }}</div>
        </div>
      @endforeach
    </div>
  </div>

  <div class="card card-dark mb-3">
    <div class="card-body">
      <form class="row g-2" method="GET" action="{{ route('admin.transaksi-sewa.index') }}">
        <div class="col-md-4">
          <input type="email" name="email" value="{{ $email }}" class="form-control" placeholder="Filter email penyewa">
        </div>
        <div class="col-md-4">
          <input type="text" name="booth" value="{{ $booth }}" class="form-control" placeholder="Cari nama booth">
        </div>
        <div class="col-md-3">
          <select name="status" class="form-select">
            <option value="">Semua Status</option>
            @foreach(($filterStatuses ?? []) as $opt)
              <option value="{{ $opt }}" {{ $status===$opt ? 'selected' : '' }}>{{ ucfirst($opt) }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-1 d-grid">
          <button class="btn btn-light text-dark"><i class="fas fa-filter"></i></button>
        </div>
      </form>
    </div>
  </div>

  <div class="card card-dark">
    <div class="card-body p-3">
      <div class="table-responsive">
        <table class="table table-hover table-striped align-middle text-center mb-0">
          <thead>
            <tr>
              <th>#</th>
              <th>Penyewa</th>
              <th>Booth</th>
              <th>Periode</th>
              <th>Total Bayar</th>
              <th>Status</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
          @forelse($rows as $i => $t)
            <tr>
              <td>{{ ($rows->firstItem() ?? 0) + $i }}</td>

              {{-- ✅ pakai field hasil join --}}
              <td>
                {{ $t->penyewa_nama ?? '-' }}<br>
                <small class="text-muted">{{ $t->penyewa_email ?? '-' }}</small>
              </td>

              <td>{{ $t->nama_booth ?? ($t->booth_id ?? '-') }}</td>
              <td>
                @php
                  $mulai = !empty($t->tanggal_mulai) ? \Carbon\Carbon::parse($t->tanggal_mulai)->format('d M Y') : '-';
                  $selesai = !empty($t->tanggal_selesai) ? \Carbon\Carbon::parse($t->tanggal_selesai)->format('d M Y') : '-';
                @endphp
                {{ $mulai }} – {{ $selesai }}
              </td>
              <td>Rp {{ number_format($t->total_bayar ?? 0, 0, ',', '.') }}</td>
              <td>
                @php
                  $st = (string)($t->status ?? '');
                  $cls = in_array($st, ['lunas','disetujui','approved']) ? 'success' : (in_array($st, ['pending','menunggu']) ? 'warning' : 'secondary');
                @endphp
                <span class="badge bg-{{ $cls }}">{{ $st !== '' ? ucfirst($st) : '-' }}</span>
              </td>

              <td class="d-flex gap-1 justify-content-center">
                {{-- Approve --}}
                @if(!in_array(($t->status ?? ''), ['lunas','disetujui','approved']))
                  <form method="POST" action="{{ route('admin.transaksi-sewa.approve', $t->id) }}">
                    @csrf
                    <button class="btn btn-sm btn-success">
                      <i class="fas fa-check me-1"></i> Approve
                    </button>
                  </form>
                @endif

                {{-- Pendingkan --}}
                @if(!in_array(($t->status ?? ''), ['pending','menunggu']))
                  <form method="POST" action="{{ route('admin.transaksi-sewa.reject', $t->id) }}">
                    @csrf
                    <button class="btn btn-sm btn-warning">
                      <i class="fas fa-undo me-1"></i> Pendingkan
                    </button>
                  </form>
                @endif
              </td>
            </tr>
          @empty
            <tr><td colspan="7" class="text-muted">Belum ada data.</td></tr>
          @endforelse
          </tbody>
        </table>
      </div>

      <div class="mt-3">{{ $rows->links() }}</div>
    </div>
  </div>
</div>
@endsection
