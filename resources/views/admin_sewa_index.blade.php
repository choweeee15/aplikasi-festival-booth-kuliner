@extends('layouts.app')
@section('title','Transaksi Sewa Booth')

@section('dashboard-css')
<style>
  .dashboard-container{padding:30px;background:linear-gradient(135deg,#4e0000,#800000);min-height:100vh;color:#f8f8f8;animation:fadeInScale 1s ease forwards}
  @keyframes fadeInScale{0%{opacity:0;transform:scale(.95)}100%{opacity:1;transform:scale(1)}}
  .title-heading{font-size:2rem;font-weight:700;color:#f8f8f8}
  .card-dark{background-color:#5a0000;border-radius:12px;border:none;color:#fff;box-shadow:0 8px 20px rgb(255 0 0 /.2)}
  .table{background:#fff;color:#000;border-radius:8px;overflow:hidden;box-shadow:0 8px 20px rgb(255 0 0 /.2)}
  .table thead th{background:linear-gradient(45deg,#7b0000,#b30000);color:#fff;font-weight:700;border:none}
  .table tbody tr:hover{background:#ffe6e6}
  .btn-success{background:linear-gradient(45deg,#218838,#28a745);border:none}
  .btn-warning{background:#ffc107;color:#212529;border:none}
  .btn-danger{background:#dc3545;border:none}
</style>
@endsection

@section('content')
<div class="dashboard-container">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="title-heading"><i class="fas fa-handshake me-2"></i> Transaksi Sewa Booth</h2>
    <button class="btn btn-success btn-sm shadow-sm" data-bs-toggle="modal" data-bs-target="#modalCreate">
      <i class="fas fa-plus me-1"></i> Tambah Transaksi
    </button>
  </div>

  @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
  @if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif

  <div class="card card-dark mb-3">
    <div class="card-body">
      <form class="row g-2" method="GET" action="{{ route('transaksi-sewa.index') }}">
        <div class="col-md-8">
          <div class="input-group">
            <span class="input-group-text bg-dark text-white border-0"><i class="fas fa-search"></i></span>
            <input type="text" class="form-control" name="q" placeholder="Cari penyewa/booth/status..." value="{{ request('q') }}">
          </div>
        </div>
        <div class="col-md-2">
          @php $pp = (int) (request('per_page') ?? 10); @endphp
          <select name="per_page" class="form-select">
            @foreach([10,20,50,100] as $n)
              <option value="{{ $n }}" {{ $pp===$n ? 'selected' : '' }}>{{ $n }}/hal</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-2 d-grid"><button class="btn btn-light text-dark fw-semibold">Terapkan</button></div>
      </form>
    </div>
  </div>

  <div class="card card-dark">
    <div class="card-body p-3">
      <div class="table-responsive">
        <table class="table table-hover table-striped align-middle text-center">
          <thead>
            <tr>
              <th>No</th>
              <th>Penyewa</th>
              <th>Booth</th>
              <th>Tgl Mulai</th>
              <th>Tgl Selesai</th>
              <th>Total Bayar</th>
              <th>Status</th>
              <th width="150">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($sewa as $i=>$s)
            <tr>
              <td>{{ method_exists($sewa,'firstItem') ? $sewa->firstItem()+$i : $i+1 }}</td>
              <td class="text-start">{{ optional($s->penyewa)->nama ?? '-' }}</td>
              <td class="text-start">{{ optional($s->booth)->nama_booth ?? '-' }}</td>
              <td>{{ $s->tanggal_mulai }}</td>
              <td>{{ $s->tanggal_selesai }}</td>
              <td>Rp {{ number_format($s->total_bayar,0,',','.') }}</td>
              <td><span class="badge bg-{{ $s->status=='disetujui'?'success':($s->status=='ditolak'?'danger':'secondary') }}">{{ ucfirst($s->status) }}</span></td>
              <td>
                <button
                  class="btn btn-sm btn-warning me-1 btn-edit"
                  data-bs-toggle="modal"
                  data-bs-target="#modalEdit"
                  data-id="{{ $s->id }}"
                  data-penyewa="{{ $s->penyewa_id }}"
                  data-booth="{{ $s->booth_id }}"
                  data-mulai="{{ $s->tanggal_mulai }}"
                  data-selesai="{{ $s->tanggal_selesai }}"
                  data-total="{{ $s->total_bayar }}"
                  data-status="{{ $s->status }}"
                ><i class="fas fa-edit"></i></button>

                <form action="{{ route('transaksi-sewa.destroy',$s->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus transaksi ini?')">
                  @csrf @method('DELETE')
                  <button class="btn btn-sm btn-danger"><i class="fas fa-trash-alt"></i></button>
                </form>
              </td>
            </tr>
            @empty
            <tr><td colspan="8" class="text-center text-muted">Belum ada data.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
      @if(method_exists($sewa,'links'))
        <div class="mt-3">{{ $sewa->appends(request()->query())->links() }}</div>
      @endif
    </div>
  </div>
</div>

{{-- Modal Create --}}
<div class="modal fade" id="modalCreate" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form action="{{ route('transaksi-sewa.store') }}" method="POST">
      @csrf
      <div class="modal-content bg-dark text-white">
        <div class="modal-header"><h5 class="modal-title">Tambah Transaksi</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
        <div class="modal-body">
          <div class="mb-3">
            <label>Penyewa</label>
            <select name="penyewa_id" class="form-select" required>
              <option value="" disabled selected>Pilih Penyewa</option>
              @foreach($penyewaList as $p)
                <option value="{{ $p->id }}">{{ $p->nama ?? $p->email }}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label>Booth</label>
            <select name="booth_id" class="form-select" required>
              <option value="" disabled selected>Pilih Booth</option>
              @foreach($boothList as $b)
                <option value="{{ $b->id }}">{{ $b->nama_booth }}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3"><label>Tanggal Mulai</label><input type="date" name="tanggal_mulai" class="form-control" required></div>
          <div class="mb-3"><label>Tanggal Selesai</label><input type="date" name="tanggal_selesai" class="form-control" required></div>
          <div class="mb-3"><label>Total Bayar</label><input type="number" name="total_bayar" class="form-control" min="0" required></div>
          <div class="mb-3">
            <label>Status</label>
            <select name="status" class="form-select" required>
              <option value="menunggu">Menunggu</option>
              <option value="disetujui">Disetujui</option>
              <option value="ditolak">Ditolak</option>
            </select>
          </div>
        </div>
        <div class="modal-footer"><button class="btn btn-success">Simpan</button><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button></div>
      </div>
    </form>
  </div>
</div>

{{-- Modal Edit (REUSABLE) --}}
<div class="modal fade" id="modalEdit" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form id="formEdit" method="POST">
      @csrf @method('PUT')
      <div class="modal-content bg-dark text-white">
        <div class="modal-header"><h5 class="modal-title">Edit Transaksi</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
        <div class="modal-body">
          <div class="mb-3">
            <label>Penyewa</label>
            <select id="edit_penyewa" name="penyewa_id" class="form-select" required>
              @foreach($penyewaList as $p)
                <option value="{{ $p->id }}">{{ $p->nama ?? $p->email }}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label>Booth</label>
            <select id="edit_booth" name="booth_id" class="form-select" required>
              @foreach($boothList as $b)
                <option value="{{ $b->id }}">{{ $b->nama_booth }}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3"><label>Tanggal Mulai</label><input id="edit_mulai" type="date" name="tanggal_mulai" class="form-control" required></div>
          <div class="mb-3"><label>Tanggal Selesai</label><input id="edit_selesai" type="date" name="tanggal_selesai" class="form-control" required></div>
          <div class="mb-3"><label>Total Bayar</label><input id="edit_total" type="number" name="total_bayar" class="form-control" min="0" required></div>
          <div class="mb-3">
            <label>Status</label>
            <select id="edit_status" name="status" class="form-select" required>
              <option value="menunggu">Menunggu</option>
              <option value="disetujui">Disetujui</option>
              <option value="ditolak">Ditolak</option>
            </select>
          </div>
        </div>
        <div class="modal-footer"><button class="btn btn-success">Simpan</button><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button></div>
      </div>
    </form>
  </div>
</div>
@endsection

@section('dashboard-js')
<script>
  document.addEventListener('click', function(e){
    if(e.target.closest('.btn-edit')){
      const btn = e.target.closest('.btn-edit');
      const id = btn.dataset.id;

      const form = document.getElementById('formEdit');
      form.action = "{{ url('/admin/transaksi-sewa') }}/" + id;

      document.getElementById('edit_penyewa').value = btn.dataset.penyewa || '';
      document.getElementById('edit_booth').value   = btn.dataset.booth   || '';
      document.getElementById('edit_mulai').value   = btn.dataset.mulai   || '';
      document.getElementById('edit_selesai').value = btn.dataset.selesai || '';
      document.getElementById('edit_total').value   = btn.dataset.total   || 0;
      document.getElementById('edit_status').value  = btn.dataset.status  || 'menunggu';
    }
  });
</script>
@endsection
