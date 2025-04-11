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
            UserSeeder::class,
            MunicipiProvinciaSeeder::class,
            RestaurantSeeder::class,

            ReservaSeeder::class,
        ]);
        Plat::factory()->count(60)->create();
    }
}
