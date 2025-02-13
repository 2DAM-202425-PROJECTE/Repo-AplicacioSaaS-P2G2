<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Ubicacio;
use Faker\Factory as FakerFactory;

class UbicacioFactory extends Factory
{
    protected $model = Ubicacio::class;

    public function definition(): array
    {
        $faker = FakerFactory::create('es_ES'); // Creem una instància de Faker amb el locale 'es_ES'

        return [

            'provincia' => $faker->state,
            'municipi' => $faker->city,
            'codi_postal' => $faker->postcode,
            //'latitud' => $faker->latitude(36.0, 43.0),
            //'longitud' => $faker->longitude(-9.0, 3.0),
        ];
    }
}
