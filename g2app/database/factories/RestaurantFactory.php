<?php

namespace Database\Factories;

use App\Models\Restaurant;
use Illuminate\Database\Eloquent\Factories\Factory;

class RestaurantFactory extends Factory
{
    protected $model = Restaurant::class;

    public function definition(): array
    {
        return [
            'id_ubicacio' => $this->faker->numberBetween(1, 100),
            'nom' => $this->faker->company,
            'descripcio' => $this->faker->paragraph,
            'telefon' => $this->faker->phoneNumber,
            'tipus_cuina' => json_encode($this->faker->randomElements(Restaurant::TIPUS_CUINA, 2)),
        ];
    }
}
