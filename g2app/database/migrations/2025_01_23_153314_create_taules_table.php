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
            $table->id('id_taula');
            $table->foreignId('id_restaurant')->constrained('restaurants');
            $table->boolean('terrassa')->default(false);
            $table->integer('capacitat');
            $table->boolean('disponible')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('taules');
    }
};
