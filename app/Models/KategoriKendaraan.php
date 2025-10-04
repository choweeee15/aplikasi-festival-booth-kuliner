<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriKendaraan extends Model
{
    protected $table = 'kategori_kendaraan';

    protected $fillable = ['nama_kategori', 'status'];

    public $timestamps = true;
}
