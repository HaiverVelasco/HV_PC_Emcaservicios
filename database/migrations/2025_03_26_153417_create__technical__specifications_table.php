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
        Schema::create('_technical__specifications', function (Blueprint $table) {
            $table->id();
            $table->enum('type', [
                'COMPUTER',
                'PRINTER',
                'SERVER',
                'NETWORK',
                'PERIPHERAL',
                'OTHER'
            ]);
            
            // Componentes
            $table->string('processor')->nullable();
            $table->string('operating_system')->nullable();
            $table->string('os_version')->nullable();
            $table->string('ram')->nullable();
            $table->string('storage')->nullable();
            $table->string('storage_type')->nullable()->comment('HDD, SSD, NVMe');
            $table->string('graphics_card')->nullable();
            
            // Características eléctricas
            $table->decimal('voltage', 5, 2)->nullable()->comment('En voltios');
            $table->decimal('amperage', 5, 2)->nullable()->comment('En amperios');
            $table->decimal('power', 7, 2)->nullable()->comment('En vatios');
            $table->integer('frequency')->nullable()->comment('En Hz');
            
            $table->text('installed_software')->nullable();
            $table->text('special_configurations')->nullable();
            
            // Relación
            $table->foreignId('equipment_id')->constrained()->cascadeOnDelete();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('_technical__specifications');
    }
};
