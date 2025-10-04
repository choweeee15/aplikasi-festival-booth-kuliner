<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Registrasi extends Model
{
    protected $table = 'registrasi';

    protected $fillable = [
        'user_id', 'kategori_id', 'layanan_id', 'nama_pemilik', 'email', 'no_hp',
        'tipe_kendaraan', 'nama_kendaraan', 'model_kendaraan',
        'jenis_permintaan', 'alamat', 'status', 'catatan_admin',
        'created_at', 'updated_at',
    ];
    

    public function layanan()
    {
        return $this->belongsTo(Layanan::class);
    }
    public function pembayaran()
{
    return $this->hasOne(Pembayaran::class, 'permintaan_id');
}
public function user()
{
    return $this->belongsTo(User::class);
}



}
