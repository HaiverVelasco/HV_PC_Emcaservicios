<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $fillable = [
        'name', 
        'description',
        'status',
        'color'
    ];

    public function equipment()
    {
        return $this->hasMany(Equipment::class);
    }
}
