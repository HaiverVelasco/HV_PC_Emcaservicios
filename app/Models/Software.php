<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Software extends Model
{
    protected $fillable = [
        'name', 
        'version', 
        'license_type'
    ];

    public function equipments()
    {
        return $this->belongsToMany(Equipment::class, 'equipment_software')
        ->using(EquipmentSoftware::class)
        ->withPivot('installation_date', 'license_key');
    }
}
