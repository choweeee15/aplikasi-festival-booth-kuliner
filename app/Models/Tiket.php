<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tiket extends Model
{
    protected $table = 'tiket';

    // sesuaikan kalau ada kolom lain
    protected $fillable = [
        'nama_tiket',
        'harga',
        'stok',         // kalau tabel kamu memang punya stok
        'deskripsi',    // opsional
        'image_path',   // atau 'gambar' kalau nama kolomnya itu
    ];

    // matikan timestamps kalau tabel tidak punya created_at/updated_at
    public $timestamps = false;

    // URL gambar siap pakai di Blade
    public function getImageUrlAttribute(): string
    {
        // Prioritas kolom gambar
        $img = $this->image_path ?? $this->gambar ?? null;

        if ($img) {
            if (preg_match('~^https?://~i', $img)) {
                return $img;
            }
            return asset('storage/'.$img);
        }
        return '/img/booth-placeholder.jpg';
    }
}
