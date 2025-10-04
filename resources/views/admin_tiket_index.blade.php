@extends('layouts.app')
@section('title','Kelola Tiket')

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
    <h2 class="title-heading"><i class="fas fa-ticket-alt me-2"></i> Kelola Tiket</h2>
    <button class="btn btn-success btn-sm shadow-sm" data-bs-toggle="modal" data-bs-target="#modalCreate">
      <i class="fas fa-plus me-1"></i> Tambah Tiket
    </button>
  </div>

  @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
  @if(session('error'))   <div class="alert alert-danger">{{ session('error') }}</div>   @endif
  @if($errors->any())
    <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
  @endif

  <div class="card card-dark mb-3">
    <div class="card-body">
      <form class="row g-2" method="GET" action="{{ route('tiket.index') }}">
        <div class="col-md-8">
          <div class="input-group">
            <span class="input-group-text bg-dark text-white border-0"><i class="fas fa-search"></i></span>
            <input type="text" class="form-control" name="q" placeholder="Cari nama tiket..." value="{{ request('q') }}">
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
              <th>Nama Tiket</th>
              <th>Harga</th>
              <th>Stok</th>
              <th width="150">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($tiket as $i=>$t)
            <tr>
              <td>{{ method_exists($tiket,'firstItem') ? $tiket->firstItem()+$i : $i+1 }}</td>
              <td class="text-start">{{ $t->nama_tiket }}</td>
              <td>Rp {{ number_format($t->harga,0,',','.') }}</td>
              <td>{{ $t->stok }}</td>
              <td>
                <button
                  class="btn btn-sm btn-warning me-1 btn-edit"
                  data-bs-toggle="modal"
                  data-bs-target="#modalEdit"
                  data-id="{{ $t->id }}"
                  data-nama="{{ $t->nama_tiket }}"
                  data-harga="{{ $t->harga }}"
                  data-stok="{{ $t->stok }}"
                ><i class="fas fa-edit"></i></button>

                <form action="{{ route('tiket.destroy',$t->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus tiket ini?')">
                  @csrf @method('DELETE')
                  <button class="btn btn-sm btn-danger"><i class="fas fa-trash-alt"></i></button>
                </form>
              </td>
            </tr>
            @empty
            <tr><td colspan="5" class="text-center text-muted">Belum ada data.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>

      @if(method_exists($tiket,'links'))
        <div class="mt-3">{{ $tiket->appends(request()->query())->links() }}</div>
      @endif
    </div>
  </div>
</div>

{{-- Modal Create --}}
<div class="modal fade" id="modalCreate" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form action="{{ route('tiket.store') }}" method="POST">
      @csrf
      <div class="modal-content bg-dark text-white">
        <div class="modal-header">
          <h5 class="modal-title">Tambah Tiket</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3"><label>Nama Tiket</label><input type="text" name="nama_tiket" class="form-control" required></div>
          <div class="mb-3"><label>Harga</label><input type="number" name="harga" class="form-control" min="0" required></div>
          <div class="mb-3"><label>Stok</label><input type="number" name="stok" class="form-control" min="0" required></div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-success">Simpan</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        </div>
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
        <div class="modal-header">
          <h5 class="modal-title">Edit Tiket</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3"><label>Nama Tiket</label><input id="edit_nama" type="text" name="nama_tiket" class="form-control" required></div>
          <div class="mb-3"><label>Harga</label><input id="edit_harga" type="number" name="harga" class="form-control" min="0" required></div>
          <div class="mb-3"><label>Stok</label><input id="edit_stok" type="number" name="stok" class="form-control" min="0" required></div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-success">Simpan</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        </div>
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
      const nama = btn.dataset.nama || '';
      const harga = btn.dataset.harga || 0;
      const stok = btn.dataset.stok || 0;

      const form = document.getElementById('formEdit');
      form.action = "{{ url('/admin/tiket') }}/" + id;

      document.getElementById('edit_nama').value = nama;
      document.getElementById('edit_harga').value = harga;
      document.getElementById('edit_stok').value = stok;
    }
  });
</script>
@endsection
