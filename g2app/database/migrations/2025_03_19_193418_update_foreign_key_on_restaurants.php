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
        Schema::table('municipios', function (Blueprint $table) {
            $table->dropForeign(['provincia_id']); // Elimina la clau forana antiga
            $table->foreign('provincia_id')->references('id')->on('provincias')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('municipios', function (Blueprint $table) {
            $table->dropForeign(['provincia_id']);
            $table->foreign('provincia_id')->references('id')->on('provincias'); // Sense cascada
        });
    }
};
