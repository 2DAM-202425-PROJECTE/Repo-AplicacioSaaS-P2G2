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
            $table->foreignId('id_usuari')->constrained('users')->onDelete('cascade');
            $table->foreignId('id_restaurant')->constrained('restaurants');
            $table->string('telefon');
            $table->date('data');
            $table->time('hora');
            $table->integer('num_persones');
            $table->unsignedTinyInteger('estat')->default(0); // Using unsignedTinyInteger and default 0
            $table->index('estat'); // Add index for query performance
            $table->boolean('terrassa')->default(false)->nullable();
            $table->text('solicituds')->nullable();
            $table->timestamps();
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
