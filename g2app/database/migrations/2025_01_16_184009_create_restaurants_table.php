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
            $table->string('nom');
            $table->foreignId('municipio_id')->nullable()->constrained('municipios');
            $table->text('descripcio');
            $table->string('telefon');
            $table->string('tipus_cuina');
            $table->time('hora_obertura');
            $table->time('hora_tancament');
            $table->string('carrer');
            $table->integer('durada_reserves');
            $table->integer('pers_max');
            $table->integer('temps_antelacio');
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
