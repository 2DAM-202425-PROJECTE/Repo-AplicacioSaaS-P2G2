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
        Schema::create('reserves', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_taula')->constrained('taules');
//          $table->foreignId('id_usuari')->constrained('usuaris');
            $table->foreignId('id_restaurant')->constrained('restaurants');
            $table->string('telefon');
            $table->date('data');
            $table->time('hora');
            $table->integer('num_persones');
            $table->string('estat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reserves');
    }
};
