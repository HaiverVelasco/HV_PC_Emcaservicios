<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Maintenance extends Model
{
    protected $fillable = [
        'equipment_id',
        'maintenance_type',
        'description',
        'date',
        'technician'
    ];

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }
}
