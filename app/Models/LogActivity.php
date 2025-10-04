<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogActivity extends Model
{
    protected $table = 'log_activities'; // Nama tabel sesuai database

    protected $fillable = [
        'username',
        'activity',
        'ip_address',
    ];

    public $timestamps = true; // otomatis kelola created_at & updated_at

    // Jika tidak pakai updated_at, bisa di-disable
    const UPDATED_AT = null;
}