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
            $table->id();
            //$table->foreignId('id_ubicacio')->constrained('ubicacions');
            $table->unsignedBigInteger('id_ubicacio');
            $table->string('nom');
            $table->text('descripcio');
            $table->string('telefon');
            $table->json('tipus_cuina');

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
