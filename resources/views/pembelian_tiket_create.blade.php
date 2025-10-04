@extends('layouts.main')
@section('title','Beli Tiket')

@section('content')
<div class="container-xxl py-5">
  <div class="container">
    <div class="row g-4">
      {{-- ====================== FORM ====================== --}}
      <div class="col-lg-7">
        <h2 class="mb-3">Beli Tiket</h2>

        @if ($errors->any())
          <div class="alert alert-danger">
            <ul class="mb-0">@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
          </div>
        @endif
        @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
        @if(session('error'))   <div class="alert alert-danger">{{ session('error') }}</div>   @endif

        @if(!isset($tiket) || $tiket->isEmpty())
          <div class="alert alert-warning">
            Belum ada data tiket. Silakan buat tiket terlebih dahulu di menu admin.
          </div>
        @else
        <form method="POST" action="{{ route('pembelian-tiket.store') }}" enctype="multipart/form-data" class="card shadow border-0" id="formTiket" novalidate>
          @csrf
          <div class="card-body">
            {{-- Step bar kecil --}}
            <div class="d-flex align-items-center gap-2 mb-3">
              <span class="badge bg-danger">1</span><span>Pilih tiket</span>
              <i class="fas fa-angle-right text-muted"></i>
              <span class="badge bg-danger">2</span><span>Upload bukti</span>
              <i class="fas fa-angle-right text-muted"></i>
              <span class="badge bg-danger">3</span><span>Kirim</span>
            </div>

            <div class="row g-3">
              {{-- Email akun (readonly) --}}
              <div class="col-12">
                <label class="form-label">Email Akun</label>
                <input type="email" class="form-control" value="{{ auth()->user()->email ?? '-' }}" readonly>
                <small class="text-muted">Email ini otomatis dari akun login & dipakai untuk mengikat pembelian.</small>
              </div>

              {{-- Pilih Tiket --}}
              <div class="col-md-7">
                <label class="form-label">Pilih Tiket <span class="text-danger">*</span></label>
                <select name="tiket_id" id="tiket_id" class="form-select" required>
                  <option value="">-- pilih --</option>
                  @foreach($tiket as $t)
                    @php
                      $harga = (int)($t->harga ?? 0);
                      $stok  = \Schema::hasColumn('tiket','stok') ? (int)($t->stok ?? 0) : '';
                      $imgUrl = method_exists($t,'getImageUrlAttribute') ? $t->image_url : (
                        ($t->gambar ?? $t->image_path) ? ( \Illuminate\Support\Str::startsWith(($t->gambar ?? $t->image_path), ['http://','https://'])
                                                          ? ($t->gambar ?? $t->image_path)
                                                          : asset('storage/'.($t->gambar ?? $t->image_path)) )
                                                        : '/img/booth-placeholder.jpg'
                      );
                      $desc = trim((string)($t->deskripsi ?? $t->keterangan ?? ''));
                    @endphp
                    <option 
                      value="{{ $t->id }}"
                      data-nama="{{ $t->nama_tiket }}"
                      data-harga="{{ $harga }}"
                      data-stok="{{ $stok }}"
                      data-img="{{ $imgUrl }}"
                      data-desc="{{ \Illuminate\Support\Str::limit($desc, 160) }}"
                    >
                      {{ $t->nama_tiket }} — Rp {{ number_format($harga,0,',','.') }}
                      @if($stok!=='') (Stok: {{ $stok }}) @endif
                    </option>
                  @endforeach
                </select>
                <small class="text-muted d-block mt-1">Kalau belum memilih, sistem otomatis menampilkan tiket pertama sebagai ringkasan.</small>
              </div>

              {{-- Jumlah --}}
              <div class="col-md-5">
                <label class="form-label">Jumlah <span class="text-danger">*</span></label>
                <div class="input-group">
                  <button class="btn btn-outline-secondary" type="button" id="qtyMinus"><i class="fas fa-minus"></i></button>
                  <input type="number" name="jumlah" id="jumlah" class="form-control text-center" min="1" max="10" value="1" required />
                  <button class="btn btn-outline-secondary" type="button" id="qtyPlus"><i class="fas fa-plus"></i></button>
                </div>
                <small class="text-muted">Maksimal 10 tiket per transaksi.</small>
              </div>

              {{-- Bukti Pembayaran --}}
              <div class="col-12">
                <label class="form-label">Upload Bukti Pembayaran (JPG/PNG/PDF, maks 5MB) <span class="text-danger">*</span></label>
                <input type="file" name="bukti" id="bukti" class="form-control" accept=".jpg,.jpeg,.png,.pdf" required />
                <small id="buktiInfo" class="text-muted d-block mt-1">Belum ada file dipilih.</small>
              </div>

              {{-- Catatan opsional --}}
              <div class="col-12">
                <label class="form-label">Catatan (opsional)</label>
                <textarea name="catatan" class="form-control" rows="2" placeholder="Contoh: minta e-receipt, dsb. (opsional)"></textarea>
              </div>
            </div>
          </div>

          <div class="card-footer d-flex flex-wrap gap-2 justify-content-between">
            <a href="{{ route('pembelian-tiket.index') }}" class="btn btn-outline-secondary">
              <i class="fas fa-list me-1"></i> Kembali ke Riwayat
            </a>
            <button id="btnSubmit" class="btn btn-danger">
              <i class="fas fa-ticket-alt me-1"></i> Simpan Pembelian
            </button>
          </div>
        </form>
        @endif
      </div>

      {{-- ====================== RINGKASAN (sticky) ====================== --}}
      <div class="col-lg-5">
        <div class="card shadow border-0 position-sticky" style="top: 90px;">
          <div class="ratio ratio-16x9 bg-light">
            <img id="sumImg" src="/img/booth-placeholder.jpg" class="card-img-top rounded-top" alt="Tiket" style="object-fit: cover;" onerror="this.src='/img/booth-placeholder.jpg'">
          </div>
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-start">
              <h5 class="mb-1" id="sumNamaTiket">—</h5>
              <span id="sumBadgeStok" class="badge bg-secondary d-none">-</span>
            </div>
            <p class="text-muted small" id="sumDesc">Pilih tiket untuk melihat deskripsi singkat.</p>

            <ul class="list-group mb-3">
              <li class="list-group-item d-flex justify-content-between align-items-center">
                Harga Satuan
                <span id="sumHarga" class="fw-semibold">Rp 0</span>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center">
                Qty
                <span id="sumQty" class="fw-semibold">1</span>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center">
                Subtotal
                <span id="sumSubtotal" class="fw-bold">Rp 0</span>
              </li>
              <li class="list-group-item d-flex justify-content-between align-items-center">
                Biaya Admin
                <span id="sumFee" class="fw-semibold">Rp 0</span>
              </li>
            </ul>

            <div class="d-flex justify-content-between align-items-center">
              <span class="fw-semibold">Total Estimasi</span>
              <span class="fs-4 fw-bold text-danger" id="sumTotal">Rp 0</span>
            </div>
          </div>
          <div class="card-footer bg-white border-0">
            <small class="text-muted d-block">
              Total akhir dihitung ulang di server. Simpan bukti transfer yang jelas agar proses verifikasi cepat.
            </small>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- ==== INLINE SCRIPT (tanpa @push) ==== --}}
<script>
(function(){
  const selectTiket = document.getElementById('tiket_id');
  const inputJumlah = document.getElementById('jumlah');
  const btnMinus    = document.getElementById('qtyMinus');
  const btnPlus     = document.getElementById('qtyPlus');
  const inputBukti  = document.getElementById('bukti');
  const btnSubmit   = document.getElementById('btnSubmit');

  // Ringkasan elemen
  const sumImg      = document.getElementById('sumImg');
  const sumNama     = document.getElementById('sumNamaTiket');
  const sumDesc     = document.getElementById('sumDesc');
  const sumBadgeStok= document.getElementById('sumBadgeStok');
  const sumHarga    = document.getElementById('sumHarga');
  const sumQty      = document.getElementById('sumQty');
  const sumSubtotal = document.getElementById('sumSubtotal');
  const sumFee      = document.getElementById('sumFee');
  const sumTotal    = document.getElementById('sumTotal');
  const buktiInfo   = document.getElementById('buktiInfo');

  // Biaya admin flat (kalau tidak dipakai, biarkan 0)
  const FEE = 0;

  const rupiah = (n) => 'Rp ' + (parseInt(n || 0)).toLocaleString('id-ID');

  function getSelectedData() {
    const opt   = selectTiket?.selectedOptions?.[0];
    const harga = parseInt(opt?.dataset?.harga || 0);
    const nama  = opt?.dataset?.nama  || '—';
    const stok  = opt?.dataset?.stok  || '';
    const img   = opt?.dataset?.img   || '/img/booth-placeholder.jpg';
    const desc  = opt?.dataset?.desc  || '';
    const qtyRaw= parseInt(inputJumlah?.value || 1);
    const qty   = Math.min(10, Math.max(1, isNaN(qtyRaw) ? 1 : qtyRaw));
    return { harga, nama, stok, img, desc, qty };
  }

  function updateSummary() {
    const { harga, nama, stok, img, desc, qty } = getSelectedData();

    inputJumlah.value     = qty;
    sumNama.textContent   = nama;
    sumHarga.textContent  = rupiah(harga);
    sumQty.textContent    = qty;
    sumSubtotal.textContent = rupiah(harga * qty);
    sumFee.textContent    = rupiah(FEE);
    sumTotal.textContent  = rupiah(harga * qty + FEE);
    sumDesc.textContent   = desc || '—';

    // Badge stok (jika ada)
    if (stok !== '') {
      const s = parseInt(stok);
      sumBadgeStok.classList.remove('d-none');
      sumBadgeStok.classList.toggle('bg-success', s > 0);
      sumBadgeStok.classList.toggle('bg-secondary', s <= 0);
      sumBadgeStok.textContent = s > 0 ? `Stok: ${s}` : 'Habis';
    } else {
      sumBadgeStok.classList.add('d-none');
    }

    // Gambar
    sumImg.src = img || '/img/booth-placeholder.jpg';
  }

  function validateForm() {
    const hasTicket = !!selectTiket?.value;
    const qty = parseInt(inputJumlah?.value || 0);
    const qtyOK = qty >= 1 && qty <= 10;

    let fileOK = false;
    if (inputBukti?.files && inputBukti.files.length > 0) {
      const f = inputBukti.files[0];
      const max = 5 * 1024 * 1024; // 5MB
      const allowed = ['image/jpeg','image/png','application/pdf'];
      fileOK = (f.size <= max) && (allowed.includes(f.type) || /\.(jpg|jpeg|png|pdf)$/i.test(f.name));
    }

    if (btnSubmit) btnSubmit.disabled = !(hasTicket && qtyOK && fileOK);
  }

  // Qty buttons
  btnMinus?.addEventListener('click', () => {
    inputJumlah.value = Math.max(1, parseInt(inputJumlah.value || 1) - 1);
    updateSummary(); validateForm();
  });
  btnPlus?.addEventListener('click', () => {
    inputJumlah.value = Math.min(10, parseInt(inputJumlah.value || 1) + 1);
    updateSummary(); validateForm();
  });

  // File info
  inputBukti?.addEventListener('change', () => {
    if (inputBukti.files && inputBukti.files.length > 0) {
      const f = inputBukti.files[0];
      buktiInfo.textContent = `${f.name} (${Math.round(f.size/1024)} KB)`;
    } else {
      buktiInfo.textContent = 'Belum ada file dipilih.';
    }
    validateForm();
  });

  // Change handlers
  selectTiket?.addEventListener('change', () => { updateSummary(); validateForm(); });
  inputJumlah?.addEventListener('input', () => { updateSummary(); validateForm(); });

  // AUTO-SELECT tiket pertama agar ringkasan tidak kosong
  if (selectTiket && !selectTiket.value && selectTiket.options.length > 1) {
    selectTiket.selectedIndex = 1; // opsi 0 adalah "-- pilih --"
  }

  // Init
  updateSummary();
  validateForm();
})();
</script>
@endsection
