<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TechnicalSpecification extends Model
{
    protected $casts = [
        'voltage' => 'decimal:2',
        'amperage' => 'decimal:2',
        'power' => 'decimal:2',
    ];

    public function equipment(): BelongsTo
    {
        return $this->belongsTo(Equipment::class);
    }
    
    // Accesor para tipo de almacenamiento traducido
    public function getTranslatedStorageTypeAttribute(): ?string
    {
        if (!$this->storage_type) return null;
        
        return [
            'HDD' => 'Disco Duro (HDD)',
            'SSD' => 'Disco de Estado Sólido (SSD)',
            'NVMe' => 'NVMe'
        ][$this->storage_type] ?? $this->storage_type;
    }
}