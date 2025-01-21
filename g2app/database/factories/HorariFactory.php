<?php


namespace Database\Factories;

use App\Models\Horari;
use App\Models\Restaurant;
use Illuminate\Database\Eloquent\Factories\Factory;

class HorariFactory extends Factory
{
    protected $model = Horari::class;

    public function definition(): array
    {
        return [
            'id_restaurant' => Restaurant::factory(),
            'dia' => $this->faker->dayOfWeek,
            'obertura' => $this->faker->time,
            'tancament' => $this->faker->time,
        ];
    }
}
