<?php

namespace Database\Factories;

use App\Models\Municipio;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as FakerFactory;

class RestaurantFactory extends Factory
{
    protected $model = Restaurant::class;

    public function definition(): array
    {
        $faker = FakerFactory::create('es_ES'); // Spanish locale

        return [
            'nom' => $faker->randomElement(Restaurant::$noms),
            'municipio_id' => Municipio::inRandomOrder()->first()->id,
            'descripcio' => $faker->paragraph,
            'telefon' => $faker->phoneNumber,
            'tipus_cuina' => $faker->randomElement(Restaurant::$TIPUS_CUINA),
            'hora_obertura' => $faker->time('H:i'),
            'hora_tancament' => $faker->time('H:i'),
            'carrer' => $faker->streetAddress,
            'user_id' =>  User::where('empresa', 1)->inRandomOrder()->first()?->id,
        ];
    }
}
