<?php

namespace Database\Factories;

use App\Models\Reserva;
use App\Models\Restaurant;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReservaFactory extends Factory
{
    protected $model = Reserva::class;

    public function definition(): array
    {
        return [
            'id_restaurant' => Restaurant::inRandomOrder()->first()->id,
            'id_usuari' => User::inRandomOrder()->first()->id,
            'data' => $this->faker->date(),
            'hora' => $this->faker->time(),
            'telefon' => $this->faker->phoneNumber,
            'num_persones' => $this->faker->numberBetween(1, 20),
            'estat' => $this->faker->randomElement([0, 1, 2, 3]), // Assuming 0: Pendent, 1: Confirmada, 2: Cancel·lada, 3: Completada
            'terrassa' => $this->faker->boolean,
            'solicituds' => $this->faker->sentence,
        ];
    }
}
