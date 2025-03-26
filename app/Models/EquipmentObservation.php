<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EquipmentObservation extends Model
{
    protected $casts = [
        'requires_followup' => 'boolean',
        'followup_date' => 'date',
        'resolved' => 'boolean',
    ];

    public function equipment(): BelongsTo
    {
        return $this->belongsTo(Equipment::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'created_by_id');
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'assigned_to_id');
    }
    
    // Accesor para tipo traducido
    public function getTranslatedTypeAttribute(): string
    {
        return [
            'OBSERVATION' => 'Observación',
            'NON_CONFORMITY' => 'No conformidad',
            'IMPROVEMENT_SUGGESTION' => 'Sugerencia de mejora'
        ][$this->type] ?? $this->type;
    }
}