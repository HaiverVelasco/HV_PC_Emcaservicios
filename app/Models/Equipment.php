<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Equipment extends Model
{
    protected $casts = [
        'purchase_date' => 'date',
        'operation_start_date' => 'date',
        'status' => 'string',
        'availability' => 'string',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class, 'current_area_id');
    }

    public function currentResponsible(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'current_responsible_id');
    }

    public function technicalSpecifications(): HasOne
    {
        return $this->hasOne(TechnicalSpecification::class);
    }

    public function maintenances(): HasMany
    {
        return $this->hasMany(Maintenance::class)->latest();
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(EquipmentAssignment::class)->latest();
    }

    public function documents(): HasMany
    {
        return $this->hasMany(EquipmentDocument::class)->latest();
    }

    public function observations(): HasMany
    {
        return $this->hasMany(EquipmentObservation::class)->latest();
    }
    
    // Accesor para estado traducido
    public function getTranslatedStatusAttribute(): string
    {
        return [
            'GOOD' => 'Bueno',
            'REGULAR' => 'Regular',
            'BAD' => 'Malo',
            'DISASSEMBLED' => 'Desarmado'
        ][$this->status] ?? $this->status;
    }
}