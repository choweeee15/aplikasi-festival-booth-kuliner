<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booth extends Model
{
    protected $table = 'booths';

    protected $fillable = [
        'nama_booth',
        'deskripsi',
        'harga_sewa',
        'status',
        'image_path',
    ];

    // karena tabel tidak punya created_at / updated_at
    public $timestamps = false;

    // URL gambar untuk dipakai langsung di Blade
    public function getImageUrlAttribute(): string
    {
        if ($this->image_path) {
            // kalau sudah full URL
            if (preg_match('~^https?://~i', $this->image_path)) {
                return $this->image_path;
            }
            return asset('storage/'.$this->image_path);
        }
        return '/img/booth-placeholder.jpg';
    }
}
