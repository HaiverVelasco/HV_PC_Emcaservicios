<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EquipmentAssignment extends Model
{
    protected $casts = [
        'assignment_date' => 'date',
        'return_date' => 'date',
    ];

    public function equipment(): BelongsTo
    {
        return $this->belongsTo(Equipment::class);
    }

    public function fromArea(): BelongsTo
    {
        return $this->belongsTo(Area::class, 'from_area_id');
    }

    public function toArea(): BelongsTo
    {
        return $this->belongsTo(Area::class, 'to_area_id');
    }

    public function previousResponsible(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'previous_responsible_id');
    }

    public function newResponsible(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'new_responsible_id');
    }

    public function authorizedBy(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'authorized_by_id');
    }
}