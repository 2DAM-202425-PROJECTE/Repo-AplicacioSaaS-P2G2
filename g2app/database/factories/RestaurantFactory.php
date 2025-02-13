<?php

namespace Database\Factories;

use App\Models\Municipi;
use App\Models\Restaurant;
use App\Models\Ubicacio;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as FakerFactory;

class RestaurantFactory extends Factory
{
    protected $model = Restaurant::class;

    public function definition(): array
    {
        $faker = FakerFactory::create('es_ES'); // Spanish locale

        return [
            'municipi_id' => Municipi::inRandomOrder()->first()->id,
            'nom' => $faker->randomElement(Restaurant::$noms),
            'descripcio' => $faker->paragraph,
            'telefon' => $faker->phoneNumber,
            'tipus_cuina' => $faker->randomElement(Restaurant::$TIPUS_CUINA),
            'hora_obertura' => $faker->time('H:i'),
            'hora_tancament' => $faker->time('H:i'),
            'carrer' => $faker->streetAddress,
        ];
    }
}
