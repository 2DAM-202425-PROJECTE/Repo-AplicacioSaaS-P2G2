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
        Schema::create('provincies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
        });

        Schema::create('municipis', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('provincia_id')->constrained('provincies');

        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('municipis');
        Schema::dropIfExists('provincies');
    }
};
