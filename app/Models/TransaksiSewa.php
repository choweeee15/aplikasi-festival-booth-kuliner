<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiSewa extends Model
{
    public $timestamps = false; // ⬅️ nonaktifkan

    protected $table = 'transaksi_sewa';

    protected $fillable = [
        'penyewa_id',
        'booth_id',
        'tanggal_mulai',
        'tanggal_selesai',
        'total_bayar',
        'status',
    ];

    public function penyewa()
    {
        return $this->belongsTo(\App\Models\Penyewa::class, 'penyewa_id');
    }

    public function booth()
    {
        return $this->belongsTo(\App\Models\Booth::class, 'booth_id');
    }
}
