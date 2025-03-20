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
        Schema::create('taules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_restaurant')->constrained('restaurants')->index()->onDelete('cascade');
            $table->boolean('terrassa')->default(false);
            $table->integer('capacitat')->default(1);
            $table->boolean('disponible')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Desactivar les restriccions de clau forana a SQLite
        DB::statement('PRAGMA foreign_keys = OFF;');

        // Eliminar els registres de la taula municipios primer
        DB::table('taules')->delete();

        Schema::dropIfExists('taules');

        // Reactivar les restriccions de clau forana a SQLite
        DB::statement('PRAGMA foreign_keys = ON;');
    }
};
