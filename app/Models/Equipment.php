<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Storage;

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
        'observations',
        'equipment_type',
        'equipment_function',
        'direct_responsible',
        'indirect_responsible',
    ];

    protected static function boot()
    {
        parent::boot();
        
        // Antes de eliminar el equipo, eliminar todas las imágenes asociadas
        static::deleting(function($equipment) {
            foreach ($equipment->images as $image) {
                Storage::disk('public')->delete($image->url);
            }
            $equipment->images()->delete();
        });
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function maintenances()
    {
        return $this->hasMany(Maintenance::class);
    }

    /**
     * Get all of the equipment's images.
     */
    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable');
    }
}
