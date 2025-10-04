<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $table = 'pembayaran';

    protected $fillable = [
        'permintaan_id', 'bukti_pembayaran', 'status', 'tanggal_upload', 'tanggal_verifikasi', 'catatan', 'jumlah_tagihan', 'rincian_biaya'
    ];

    public function registrasi()
    {
        return $this->belongsTo(Registrasi::class, 'permintaan_id');
    }
}

