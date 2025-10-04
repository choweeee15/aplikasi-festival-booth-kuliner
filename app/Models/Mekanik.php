<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mekanik extends Model
{
    use HasFactory;

    protected $table = 'mekanik';  // Nama tabel, kalau plural (mekaniks) Laravel otomatis, jadi ini penting

    protected $primaryKey = 'id';  // Default sudah 'id', tapi kalau beda, sesuaikan

    protected $fillable = ['nama', 'telepon', 'status']; // Kolom yang boleh diisi lewat mass assignment
}
