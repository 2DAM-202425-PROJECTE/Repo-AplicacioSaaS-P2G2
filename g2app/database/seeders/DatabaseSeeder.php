<?php

namespace Database\Seeders;
use App\Models\Plat;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call([
            MunicipiProvinciaSeeder::class,
            RestaurantSeeder::class,
            TaulaSeeder::class,

            ReservaSeeder::class,
        ]);
        Plat::factory()->count(60)->create();
    }
}
