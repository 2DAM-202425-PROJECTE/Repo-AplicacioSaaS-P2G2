<?php

namespace Database\Factories;

use App\Models\Restaurant;
use App\Models\Taula;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaulaFactory extends Factory
{
    protected $model = Taula::class;

    public function definition(): array
    {

        $restaurant = Restaurant::inRandomOrder()->first();

        if (!$restaurant) {
            $restaurant = Restaurant::factory()->create();
        }

        return [
            'id_restaurant' => $restaurant->id, // Use the actual restaurant ID
            'terrassa' => $this->faker->boolean,
            'capacitat' => $this->faker->numberBetween(2, 10),
            'disponible' => $this->faker->boolean,
        ];
    }
}
