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
        Schema::create('_maintenances', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->enum('type', [
                'PREVENTIVE',    // Preventivo
                'CORRECTIVE',    // Correctivo
                'INSTALLATION',  // Instalación
                'DISASSEMBLY',   // Desmontaje
                'UPGRADE',       // Actualización
                'CALIBRATION'    // Calibración
            ]);
            
            $table->text('description');
            $table->text('performed_actions');
            $table->text('recommendations')->nullable();
            
            // Detalles de fallas
            $table->boolean('depreciation')->default(false);
            $table->boolean('bad_operation')->default(false);
            $table->boolean('bad_installation')->default(false);
            $table->boolean('accessories_issue')->default(false);
            $table->boolean('unknown_cause')->default(false);
            $table->boolean('no_issues_found')->default(false);
            
            // Responsables
            $table->string('performed_by')->comment('Nombre del técnico/proveedor');
            $table->enum('service_type', ['INTERNAL', 'EXTERNAL']);
            $table->decimal('cost', 12, 2)->nullable();
            
            // Relaciones
            $table->foreignId('equipment_id')->constrained()->cascadeOnDelete();
            $table->foreignId('requesting_area_id')->constrained('areas');
            $table->foreignId('responsible_employee_id')->nullable()->constrained('employees');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('_maintenances');
    }
};
