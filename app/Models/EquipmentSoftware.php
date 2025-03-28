<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipmentSoftware extends Model
{
    
        protected $table = 'equipment_software';


        public $incrementing = true;

      // Specify the fillable fields if you have additional columns in the pivot table
        protected $fillable = [
            'equipment_id', 
            'software_id', 
          // Add any additional fields like installation_date, license_key, etc.
        ];

      // Relationships if needed
        public function equipment()
        {
            return $this->belongsTo(Equipment::class);
        }

        public function software()
        {
            return $this->belongsTo(Software::class);
        }
}
