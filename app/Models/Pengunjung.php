<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengunjung extends Model
{
    protected $table = 'pengunjungs';
    protected $fillable = ['nama','email','no_hp','password'];

    public function pembelianTiket()
    {
        return $this->hasMany(PembelianTiket::class, 'pengunjung_id');
    }
}
