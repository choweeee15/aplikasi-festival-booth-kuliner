@extends('layouts.main')
@section('title','Pembelian Tiket')

@section('content')
<div class="container-xxl py-5">
  <div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h2 class="mb-0">Pembelian Tiket</h2>
        <small class="text-muted">Riwayat pembelian tiket kamu.</small>
      </div>
      <a href="{{ route('pembelian-tiket.create') }}" class="btn btn-danger">
        <i class="fas fa-plus me-1"></i> Beli Tiket
      </a>
    </div>

    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if(session('error'))   <div class="alert alert-danger">{{ session('error') }}</div>   @endif

    <div class="card shadow border-0">
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0 align-middle text-center">
            <thead>
              <tr>
                <th>#</th>
                <th>Tiket</th>
                <th>Jumlah</th>
                <th>Total</th>
                <th>Status Bayar</th>
                <th>Tanggal</th>
                <th>QR</th>
              </tr>
            </thead>
            <tbody>
              @forelse($items as $i => $row)
              <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ optional($row->tiket)->nama_tiket ?? $row->tiket_id }}</td>
                <td>{{ $row->jumlah }}</td>
                <td>Rp {{ number_format($row->total_harga,0,',','.') }}</td>
                <td>
                  <span class="badge bg-{{ $row->status_bayar === 'lunas' ? 'success' : 'warning' }}">
                    {{ $row->status_bayar === 'pending' ? 'Pending' : ucfirst($row->status_bayar) }}
                  </span>
                </td>
                <td>{{ $row->created_at ? \Carbon\Carbon::parse($row->created_at)->format('d M Y') : '-' }}</td>
                <td>
                  @if(($row->status_bayar ?? '') === 'lunas')
                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#qrModal{{ $row->id }}">
                      <i class="fas fa-qrcode me-1"></i> Lihat QR
                    </button>

                    {{-- MODAL QR --}}
                    <div class="modal fade" id="qrModal{{ $row->id }}" tabindex="-1" aria-hidden="true">
                      <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title">QR Tiket • {{ $row->kode }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body text-center">
                            {{-- SVG utama --}}
                            <img id="qrImg-{{ $row->id }}"
                                 src="{{ route('pembelian-tiket.qr', $row->id) }}?v={{ time() }}"
                                 alt="QR Tiket"
                                 class="img-fluid"
                                 style="max-width: 320px;">
                            {{-- Fallback apabila img SVG diblokir --}}
                            <details class="mt-3">
                              <summary class="small text-muted">Gagal tampil?</summary>
                              <div class="pt-2">
                                <object type="image/svg+xml"
                                        data="{{ route('pembelian-tiket.qr', $row->id) }}?v={{ time() }}"
                                        width="320"></object>
                                <div class="small text-muted">Jika tetap tidak tampil, gunakan tombol download di bawah.</div>
                              </div>
                            </details>
                          </div>
                          <div class="modal-footer">
                            {{-- Download SVG langsung dari server (aman) --}}
                            <a class="btn btn-outline-secondary"
                               href="{{ route('pembelian-tiket.qr', $row->id) }}"
                               download="qr-{{ $row->kode }}.svg">
                              <i class="fas fa-download me-1"></i> Download SVG
                            </a>
                            {{-- Download PNG via client-side (Canvas) --}}
                            <button type="button"
                                    class="btn btn-outline-secondary"
                                    onclick="downloadQrPng('{{ route('pembelian-tiket.qr', $row->id) }}','qr-{{ $row->kode }}.png')">
                              <i class="fas fa-download me-1"></i> Download PNG
                            </button>
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Tutup</button>
                          </div>
                        </div>
                      </div>
                    </div>
                  @else
                    <span class="text-muted">-</span>
                  @endif
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="7" class="text-muted">Belum ada pembelian.</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div>
</div>

{{-- JS helper: Konversi SVG → PNG di sisi client --}}
<script>
async function downloadQrPng(svgUrl, filename) {
  try {
    // Ambil SVG sebagai teks
    const res = await fetch(svgUrl, {cache: 'no-store'});
    if (!res.ok) throw new Error('Gagal mengambil SVG');
    const svgText = await res.text();

    // Buat blob SVG
    const svgBlob = new Blob([svgText], { type: 'image/svg+xml' });
    const svgBlobUrl = URL.createObjectURL(svgBlob);

    // Gambar ke <canvas> lewat <img>
    const img = new Image();
    img.crossOrigin = 'anonymous'; // jaga-jaga
    img.onload = () => {
      const size = Math.max(img.width || 320, img.height || 320); // default 320
      const canvas = document.createElement('canvas');
      // SimpleSoftwareIO default QR 320x320 → pakai 320
      canvas.width = 320;
      canvas.height = 320;
      const ctx = canvas.getContext('2d');
      // background putih agar PNG tidak transparan
      ctx.fillStyle = '#fff';
      ctx.fillRect(0, 0, canvas.width, canvas.height);
      // draw SVG ke canvas (scale proporsional)
      ctx.drawImage(img, 0, 0, canvas.width, canvas.height);

      // toDataURL jadi PNG
      const pngUrl = canvas.toDataURL('image/png');

      // trigger download
      const a = document.createElement('a');
      a.href = pngUrl;
      a.download = filename || 'qr.png';
      document.body.appendChild(a);
      a.click();
      a.remove();

      // cleanup
      URL.revokeObjectURL(svgBlobUrl);
    };
    img.onerror = () => {
      URL.revokeObjectURL(svgBlobUrl);
      alert('Tidak bisa memproses SVG ke PNG di browser ini.');
    };
    img.src = svgBlobUrl;
  } catch (e) {
    console.error(e);
    alert('Gagal membuat PNG. Coba download SVG saja.');
  }
}
</script>
@endsection
