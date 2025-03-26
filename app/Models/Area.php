<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Area extends Model
{
    protected $casts = [
        'type' => 'string',
    ];

    public function equipments(): HasMany
    {
        return $this->hasMany(Equipment::class, 'current_area_id');
    }

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }

    public function maintenances(): HasMany
    {
        return $this->hasMany(Maintenance::class, 'requesting_area_id');
    }
    
    // Accesor para nombre traducido
    public function getTranslatedNameAttribute(): string
    {
        return $this->name_es ?? $this->name_en ?? str_replace('_', ' ', $this->type);
    }
}
