<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PembelianTiket extends Model
{
    protected $table = 'pembelian_tiket';
    public $timestamps = false; // tabel kamu tidak punya updated_at

    protected $fillable = [
        'kode',
        'qr_token',
        'pengunjung_id',
        'tiket_id',
        'jumlah',
        'total_harga',
        'qr_code',
        'status_bayar',
        'checked_in_at',
    ];

    protected $dates = ['checked_in_at'];

    // ⬇️ Relasi yang dibutuhkan Blade & controller
    public function tiket(): BelongsTo
    {
        // foreign key: tiket_id (sesuai DB kamu)
        return $this->belongsTo(Tiket::class, 'tiket_id');
    }

    public function pengunjung(): BelongsTo
    {
        return $this->belongsTo(Pengunjung::class, 'pengunjung_id');
    }
}
