@extends('layouts.app')
@section('title', 'Kelola Booth')

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
  .thumb { width:64px; height:48px; object-fit:cover; border-radius:6px; }
  .small-text { font-size:.85rem; opacity:.85; }
</style>
@endsection

@section('content')
<div class="dashboard-container">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="title-heading"><i class="fas fa-store me-2"></i> Kelola Booth</h2>
    <button class="btn btn-success btn-sm shadow-sm" data-bs-toggle="modal" data-bs-target="#modalCreate">
      <i class="fas fa-plus me-1"></i> Tambah Booth
    </button>
  </div>

  @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
  @if(session('error'))   <div class="alert alert-danger">{{ session('error') }}</div>   @endif
  @if($errors->any())
    <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
  @endif

  <div class="card card-dark mb-3">
    <div class="card-body">
      <form class="row g-2" method="GET" action="{{ route('booths.index') }}">
        <div class="col-md-8">
          <div class="input-group">
            <span class="input-group-text bg-dark text-white border-0"><i class="fas fa-search"></i></span>
            <input type="text" class="form-control" name="q" placeholder="Cari booth/deskripsi..." value="{{ request('q') }}">
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
        <div class="col-md-2 d-grid">
          <button class="btn btn-light text-dark fw-semibold">Terapkan</button>
        </div>
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
              <th>Gambar</th>
              <th>Nama</th>
              <th>Deskripsi</th>
              <th>Harga Sewa</th>
              <th>Status</th>
              <th width="170">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($booths as $i=>$b)
            <tr>
              <td>{{ method_exists($booths,'firstItem') ? $booths->firstItem()+$i : $i+1 }}</td>
              <td>
                <img src="{{ $b->image_url }}" class="thumb" alt="thumb">
              </td>
              <td class="text-start">{{ $b->nama_booth }}</td>
              <td class="text-start">{{ \Illuminate\Support\Str::limit($b->deskripsi, 80) }}</td>
              <td>Rp {{ number_format($b->harga_sewa,0,',','.') }}</td>
              <td><span class="badge bg-{{ $b->status=='tersedia'?'success':'secondary' }}">{{ ucfirst($b->status) }}</span></td>
              <td>
                <button
                  class="btn btn-sm btn-warning me-1 btn-edit"
                  data-bs-toggle="modal"
                  data-bs-target="#modalEdit"
                  data-id="{{ $b->id }}"
                  data-nama="{{ $b->nama_booth }}"
                  data-deskripsi="{{ $b->deskripsi }}"
                  data-harga="{{ $b->harga_sewa }}"
                  data-status="{{ $b->status }}"
                  data-image="{{ $b->image_url }}"
                ><i class="fas fa-edit"></i></button>

                <form action="{{ route('booths.destroy',$b->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus booth ini?')">
                  @csrf @method('DELETE')
                  <button class="btn btn-sm btn-danger"><i class="fas fa-trash-alt"></i></button>
                </form>
              </td>
            </tr>
            @empty
            <tr><td colspan="7" class="text-center text-muted">Belum ada data.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>

      @if(method_exists($booths,'links'))
        <div class="mt-3">{{ $booths->appends(request()->query())->links() }}</div>
      @endif
    </div>
  </div>
</div>

{{-- Modal Create --}}
<div class="modal fade" id="modalCreate" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form action="{{ route('booths.store') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <div class="modal-content bg-dark text-white">
        <div class="modal-header">
          <h5 class="modal-title">Tambah Booth</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3"><label>Nama Booth</label><input type="text" name="nama_booth" class="form-control" required></div>
          <div class="mb-3"><label>Deskripsi</label><textarea name="deskripsi" class="form-control" rows="3"></textarea></div>
          <div class="mb-3"><label>Harga Sewa</label><input type="number" name="harga_sewa" class="form-control" min="0" required></div>
          <div class="mb-3">
            <label>Status</label>
            <select name="status" class="form-select" required>
              <option value="tersedia">Tersedia</option>
              <option value="tersewa">Tersewa</option>
            </select>
          </div>
          <div class="mb-3">
            <label>Gambar (JPG/PNG/WEBP, maks 2MB)</label>
            <input type="file" name="image" class="form-control" accept=".jpg,.jpeg,.png,.webp">
            <small class="small-text d-block mt-1">Opsional. Jika kosong, akan memakai placeholder.</small>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-success">Simpan</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        </div>
      </div>
    </form>
  </div>
</div>

{{-- Modal Edit --}}
<div class="modal fade" id="modalEdit" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form id="formEdit" method="POST" enctype="multipart/form-data">
      @csrf @method('PUT')
      <div class="modal-content bg-dark text-white">
        <div class="modal-header">
          <h5 class="modal-title">Edit Booth</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3"><label>Nama Booth</label><input id="edit_nama" type="text" name="nama_booth" class="form-control" required></div>
          <div class="mb-3"><label>Deskripsi</label><textarea id="edit_deskripsi" name="deskripsi" class="form-control" rows="3"></textarea></div>
          <div class="mb-3"><label>Harga Sewa</label><input id="edit_harga" type="number" name="harga_sewa" class="form-control" min="0" required></div>
          <div class="mb-3">
            <label>Status</label>
            <select id="edit_status" name="status" class="form-select" required>
              <option value="tersedia">Tersedia</option>
              <option value="tersewa">Tersewa</option>
            </select>
          </div>

          <div class="mb-2">
            <label>Gambar Saat Ini</label>
            <div class="d-flex align-items-center gap-2">
              <img id="edit_preview" src="/img/booth-placeholder.jpg" class="thumb" alt="preview">
              <span id="edit_filename" class="small-text">—</span>
            </div>
          </div>
          <div class="mb-3">
            <label>Ganti Gambar (JPG/PNG/WEBP, maks 2MB)</label>
            <input type="file" name="image" id="edit_image" class="form-control" accept=".jpg,.jpeg,.png,.webp">
            <div class="form-check mt-2">
              <input class="form-check-input" type="checkbox" value="1" id="hapus_gambar" name="hapus_gambar">
              <label class="form-check-label" for="hapus_gambar">Hapus gambar</label>
            </div>
          </div>
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
  // Edit: isi form & set action ke /admin/booths/{id}
  document.addEventListener('click', function(e){
    if(e.target.closest('.btn-edit')){
      const btn   = e.target.closest('.btn-edit');
      const id    = btn.dataset.id;
      const nama  = btn.dataset.nama || '';
      const desk  = btn.dataset.deskripsi || '';
      const harga = btn.dataset.harga || 0;
      const status= btn.dataset.status || 'tersedia';
      const img   = btn.dataset.image || '/img/booth-placeholder.jpg';

      const form  = document.getElementById('formEdit');
      form.action = "{{ url('/admin/booths') }}/" + id;

      document.getElementById('edit_nama').value = nama;
      document.getElementById('edit_deskripsi').value = desk;
      document.getElementById('edit_harga').value = harga;
      document.getElementById('edit_status').value = status;

      // preview & filename
      const preview = document.getElementById('edit_preview');
      const fname   = document.getElementById('edit_filename');
      preview.src   = img;
      try {
        const u = new URL(img, window.location.origin);
        fname.textContent = u.pathname.split('/').pop();
      } catch(_) {
        fname.textContent = '-';
      }

      // reset kontrol gambar
      document.getElementById('hapus_gambar').checked = false;
      const fileInput = document.getElementById('edit_image');
      if (fileInput) fileInput.value = '';
    }
  });

  // Tampilkan nama file saat pilih gambar di edit
  document.addEventListener('change', function(e){
    if (e.target && e.target.id === 'edit_image') {
      const f = e.target.files?.[0];
      const fname = document.getElementById('edit_filename');
      const prev  = document.getElementById('edit_preview');
      if (f) {
        fname.textContent = f.name + ' (' + Math.round(f.size/1024) + ' KB)';
        // preview memakai FileReader
        const reader = new FileReader();
        reader.onload = (ev) => { prev.src = ev.target.result; };
        reader.readAsDataURL(f);
      }
    }
  });
</script>
@endsection
