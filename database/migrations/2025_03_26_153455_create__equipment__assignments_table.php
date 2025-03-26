<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('_equipment__assignments', function (Blueprint $table) {
            $table->id();
            $table->date('assignment_date');
            $table->date('return_date')->nullable();
            $table->text('assignment_reason');
            $table->text('return_reason')->nullable();
            $table->text('condition_notes')->nullable();
            
            // Relaciones
            $table->foreignId('equipment_id')->constrained();
            $table->foreignId('from_area_id')->constrained('areas');
            $table->foreignId('to_area_id')->constrained('areas');
            $table->foreignId('previous_responsible_id')->nullable()->constrained('employees');
            $table->foreignId('new_responsible_id')->nullable()->constrained('employees');
            $table->foreignId('authorized_by_id')->constrained('employees');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('_equipment__assignments');
    }
};
