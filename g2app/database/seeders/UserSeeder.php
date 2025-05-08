<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->count(25)->create();

        User::factory()->create([
            'name' => 'User Empresa',
            'email' => 'empresa@email.com',
            'password' => bcrypt('password'),
            'empresa' => true,
            'email_verified_at' => now(),
            'remember_token' => \Str::random(10),
        ]);

        User::factory()->create([
            'name' => 'User Normal',
            'email' => 'usuari@email.com',
            'password' => bcrypt('password'),
            'empresa' => false,
            'email_verified_at' => now(),
            'remember_token' => \Str::random(10),
        ]);
    }


}
