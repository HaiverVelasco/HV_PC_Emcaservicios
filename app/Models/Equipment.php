<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\EquipmentSoftware;



class Equipment extends Model
{

    protected $fillable = [
        'area_id',
        'company_name',
        'city',
        'inventory_code',
        'last_update_date',
        'equipment_name',
        'brand',
        'model',
        'reference',
        'serial_number',
        'manufacturer',
        'acquisition_date',
        'equipment_location',
        'value',
        'installation',
        'warranty',
        'operation_start_date',
        'technical_brand_model',
        'processor',
        'operating_system',
        'graphic_card',
        'ram_memory',
        'storage',
        'status',
        'maintenance_type',
        'depreciation',
        'bad_operation',
        'bad_installation',
        'accessories',
        'failure',
        'observations'
    ];

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function maintenances()
    {
        return $this->hasMany(Maintenance::class);
    }

    public function softwares()
    {
        return $this->belongsToMany(Software::class, 'equipment_software')
        ->using(EquipmentSoftware::class)
        ->withPivot('installation_date', 'license_key');
    }
}
