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
        Schema::create('_employees', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id')->unique()->comment('ID interno');
            $table->string('document_number')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('position')->comment('Cargo');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('extension')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('area_id')->constrained();
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
        Schema::dropIfExists('_employees');
    }
};
