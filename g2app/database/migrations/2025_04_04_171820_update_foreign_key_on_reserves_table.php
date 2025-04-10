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
        Schema::table('reserves', function (Blueprint $table) {
            // Elimina la foreign key actual
            $table->dropForeign(['id_restaurant']);

            // Afegeix la nova foreign key amb onDelete('cascade')
            $table->foreign('id_restaurant')
                ->references('id')->on('restaurants')
                ->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reserves', function (Blueprint $table) {
            $table->dropForeign(['id_restaurant']);

            // Torna a crear la foreign key sense cascade
            $table->foreign('id_restaurant')
                ->references('id')->on('restaurants');
        });
    }

};
