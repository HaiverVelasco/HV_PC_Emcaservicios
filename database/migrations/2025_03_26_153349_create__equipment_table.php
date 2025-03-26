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
        Schema::create('_equipment', function (Blueprint $table) {
            $table->id();
            $table->string('inventory_code')->unique()->comment('Código de inventario');
            $table->string('name_es')->comment('Nombre para mostrar en UI');
            $table->string('name_en')->nullable()->comment('Nombre técnico en inglés');
            $table->string('brand');
            $table->string('model');
            $table->string('reference')->nullable();
            $table->string('serial_number')->unique();
            $table->string('manufacturer')->nullable();
            $table->date('purchase_date');
            $table->decimal('purchase_value', 12, 2)->nullable();
            $table->string('supplier')->nullable();
            $table->integer('warranty_months')->nullable();
            $table->date('operation_start_date')->nullable();
            
            $table->enum('status', [
                'GOOD',         // Bueno
                'REGULAR',       // Regular
                'BAD',          // Malo
                'DISASSEMBLED'   // Desarmado
            ])->default('GOOD');
            
            $table->enum('availability', [
                'AVAILABLE',     // Disponible
                'IN_USE',        // En uso
                'MAINTENANCE',   // En mantenimiento
                'DECOMMISSIONED' // Dado de baja
            ])->default('AVAILABLE');
            
            $table->text('observations')->nullable();
            
            // Relaciones
            $table->foreignId('current_area_id')->constrained('areas');
            $table->foreignId('current_responsible_id')->nullable()->constrained('employees');
            $table->foreignId('company_id')->constrained();
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('_equipment');
    }
};
