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
        Schema::create('_equipment__observations', function (Blueprint $table) {
            $table->id();
            $table->text('content');
            $table->enum('type', [
                'OBSERVATION',
                'NON_CONFORMITY',
                'IMPROVEMENT_SUGGESTION'
            ]);
            $table->boolean('requires_followup')->default(false);
            $table->date('followup_date')->nullable();
            $table->boolean('resolved')->default(false);
            
            // Relaciones
            $table->foreignId('equipment_id')->constrained()->cascadeOnDelete();
            $table->foreignId('created_by_id')->constrained('employees');
            $table->foreignId('assigned_to_id')->nullable()->constrained('employees');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('_equipment__observations');
    }
};
