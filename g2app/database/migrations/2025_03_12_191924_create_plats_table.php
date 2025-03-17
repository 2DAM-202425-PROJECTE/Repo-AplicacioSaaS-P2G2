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
        Schema::create('plats', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('descripcio')->nullable();
            $table->foreignId('id_restaurant')->constrained('restaurants')->index();
            $table->decimal('preu');
            $table->string('foto')->nullable();
            $table->boolean('vegetaria')->default(true);
            $table->boolean('vega')->default(true);
            $table->boolean('kosher')->default(true);
            $table->boolean('halal')->default(true);
            $table->boolean('keto')->default(true);
            $table->boolean('carn_vermella')->default(true);
            $table->boolean('gluten')->default(true);
            $table->boolean('lactics')->default(true);
            $table->boolean('crustaci')->default(true);
            $table->boolean('ous')->default(true);
            $table->boolean('lupines')->default(true);
            $table->boolean('mostassa')->default(true);
            $table->boolean('cacahuats')->default(true);
            $table->boolean('soja')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plats');
    }
};
