<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Restaurant;
use App\Models\User;

class RestaurantSeeder extends Seeder
{
    public function run(): void
    {
        //Agafem tots els usuaris que son empreses
        $empresaUsers = User::where('empresa', true)->get();

        // Per cada usuari empresa, creem un restaurant associat
        foreach ($empresaUsers as $user) {
            Restaurant::factory()->create([
                'user_id' => $user->id,
            ]);
        }
    }
}
