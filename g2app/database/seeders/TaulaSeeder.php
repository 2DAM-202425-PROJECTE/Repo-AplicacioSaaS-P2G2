<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Taula;
use App\Models\Restaurant;

class TaulaSeeder extends Seeder
{
    public function run(): void
    {
        $restaurants = Restaurant::all();

        foreach ($restaurants as $restaurant) {
            Taula::factory()->count(4)->create([
                'id_restaurant' => $restaurant->id,
            ]);
        }
    }
}
