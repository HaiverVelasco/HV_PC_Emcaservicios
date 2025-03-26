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
        Schema::create('_companies', function (Blueprint $table) {
            $table->id();
            $table->string('legal_name')->comment('Razón social');
            $table->string('commercial_name')->nullable();
            $table->string('tax_id')->unique()->comment('NIT/RUT');
            $table->string('address');
            $table->string('city');
            $table->string('state');
            $table->string('country')->default('Colombia');
            $table->string('phone');
            $table->string('fax')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->text('legal_representative')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('_companies');
    }
};
