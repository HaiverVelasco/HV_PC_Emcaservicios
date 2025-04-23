<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Maintenance extends Model
{
    protected $fillable = [
        'equipment_id',
        'type', // Cambiado de 'maintenance_type' a 'type' para coincidir con la migración
        'description',
        'date',
        'technician',
        // Campos trasladados desde la tabla de equipos
        'depreciation',
        'bad_operation',
        'bad_installation',
        'accessories',
        'failure'
    ];

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }
}
