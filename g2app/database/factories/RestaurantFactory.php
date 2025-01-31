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
            'id_ubicacio' => $this->faker->numberBetween(1, 10),
            'nom' => $this->faker->company,
            'descripcio' => $this->faker->paragraph,
            'telefon' => $this->faker->phoneNumber,
            'tipus_cuina' => $this->faker->randomElement(Restaurant::$TIPUS_CUINA),
            'hora_obertura' => $this->faker->time('H:i'),
            'hora_tancament' => $this->faker->time('H:i'),
        ];
    }
}

