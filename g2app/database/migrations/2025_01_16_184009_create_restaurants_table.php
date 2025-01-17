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
        Schema::create('restaurants', function (Blueprint $table) {
            $table->id('id_restaurant');
            $table->foreignId('id_ubicacio')->constrained('ubicacions');
            $table->string('nom');
            $table->string('horari');
            $table->text('descripcio');
            $table->string('telefon');
            $table->json('tipus_cuina');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurants');
    }
};
