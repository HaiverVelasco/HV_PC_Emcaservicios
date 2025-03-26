<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\MaintenanceServiceType;

class Maintenance extends Model
{
    protected $casts = [
    'date' => 'datetime:Y-m-d',  // Formato específico
    'cost' => 'decimal:2',
    'depreciation' => 'boolean',
    'bad_operation' => 'boolean',
    'bad_installation' => 'boolean',
    'no_issues_found' => 'boolean',
    'service_type' => 'string'
    ];

    public function equipment(): BelongsTo
    {
        return $this->belongsTo(Equipment::class);
    }

    public function requestingArea(): BelongsTo
    {
        return $this->belongsTo(Area::class, 'requesting_area_id');
    }

    public function responsibleEmployee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'responsible_employee_id');
    }
    
    // Accesor para tipo de mantenimiento traducido
    public function getTranslatedTypeAttribute(): string
    {
        return [
            'PREVENTIVE' => 'Preventivo',
            'CORRECTIVE' => 'Correctivo',
            'INSTALLATION' => 'Instalación',
            'DISASSEMBLY' => 'Desmontaje',
            'UPGRADE' => 'Actualización',
            'CALIBRATION' => 'Calibración'
        ][$this->type] ?? $this->type;
    }
}