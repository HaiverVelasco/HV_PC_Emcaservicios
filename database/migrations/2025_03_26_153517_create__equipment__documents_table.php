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
        Schema::create('_equipment__documents', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type');
            $table->string('file_path');
            $table->date('expiration_date')->nullable();
            $table->text('notes')->nullable();
            
            // Relaciones
            $table->foreignId('equipment_id')->constrained()->cascadeOnDelete();
            $table->foreignId('uploaded_by_id')->constrained('employees');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('_equipment__documents');
    }
};
