<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    /*
    public function up(): void
    {
        Schema::create('ubicacio', function (Blueprint $table) {
            $table->id('id_ubicacio');
          //  $table->string('comunitat');
            $table->string('provincia');
            $table->string('municipi');
            $table->string('codi_postal');
           // $table->decimal('latitud', 10, 7)->nullable();
           // $table->decimal('longitud', 10, 7)->nullable();
        });
    }
    */


    /**
     * Reverse the migrations.
     */
    /*
    public function down(): void
    {
        Schema::dropIfExists('ubicacio');
    }
    */
};
