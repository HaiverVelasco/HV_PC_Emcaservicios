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
        Schema::create('_areas', function (Blueprint $table) {
            $table->id();
            $table->enum('type', [
                'MANAGEMENT',          // GERENCIA
                'INTERNAL_CONTROL',     // CONTROL INTERNO
                'ADMINISTRATIVE',       // ADMINISTRATIVA
                'LEGAL',                // AREA JURIDICA
                'FINANCIAL',            // AREA FINANCIERA
                'TECHNICAL',            // AREA TECNICA
                'PLANNING'              // AREA PLANEACION
            ])->unique();
            $table->string('name_es')->comment('Nombre en español para la UI');
            $table->string('manager')->nullable()->comment('Responsable del área');
            $table->string('contact_phone')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('location')->nullable()->comment('Ubicación física del área');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('_areas');
    }
};
