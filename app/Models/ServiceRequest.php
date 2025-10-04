<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'date_time', 'owner_name', 'vehicle_name', 'vehicle_reg_no', 'assigned_to', 'service', 'status'
    ];

    public function mekanik()
    {
        return $this->belongsTo(Mekanik::class, 'assigned_to');
    }
}
