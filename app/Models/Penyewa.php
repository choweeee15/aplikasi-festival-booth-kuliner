<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penyewa extends Model
{
    protected $table = 'penyewas';
    protected $fillable = ['nama','email','no_hp','alamat'];

    public function transaksiSewa()
    {
        return $this->hasMany(TransaksiSewa::class, 'penyewa_id');
    }
}
