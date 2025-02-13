<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ubicacio;

class UbicacioSeeder extends Seeder
{
    public function run(): void
    {
        Ubicacio::factory()->count(16)->create();
    }
}
