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
        Schema::create('equipment', function (Blueprint $table) {
            $table->id();
            $table->foreignId('area_id')->nullable()->constrained()->onDelete('set null');

            // Informacion General
            $table->string('company_name')->nullable();
            $table->string('city')->nullable();
            $table->string('inventory_code')->nullable();
            $table->date('last_update_date')->nullable();
            
            $table->string('equipment_name')->nullable();
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->string('reference')->nullable();
            $table->string('serial_number')->nullable();
            $table->string('manufacturer')->nullable();
            $table->date('acquisition_date')->nullable();
            $table->string('equipment_location')->nullable();
            $table->decimal('value', 10, 2)->nullable();
            $table->string('installation')->nullable();
            $table->string('warranty')->nullable();
            $table->date('operation_start_date')->nullable();
            
            // Informacion Tecnia
            $table->string('technical_brand_model')->nullable();
            $table->string('processor')->nullable();
            $table->string('operating_system')->nullable();
            $table->string('graphic_card')->nullable();
            $table->string('ram_memory')->nullable();
            $table->string('storage')->nullable();
            
            // Estado del Equipo
            $table->enum('status', [
                'Bueno', 
                'Regular', 
                'Malo', 
                'Deshabilitado'
                ])->nullable();
            
            // Tipo de Mantenimiento
            $table->enum('maintenance_type', [
                'Preventive', 
                'Corrective', 
                'Installation', 
                'Disassembly'
            ])->nullable();
            
            // Fallas Destectadas
            $table->string('depreciation')->nullable();
            $table->string('bad_operation')->nullable();
            $table->string('bad_installation')->nullable();
            $table->string('accessories')->nullable();
            $table->enum('failure', ['Unknown', 'No Failures'])->nullable();
            
            $table->text('observations')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipment');
    }
};
