<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Restaurant;
use Inertia\Inertia;

class UserController extends Controller
{

    //PAssar correctament amb inhertia
    public function showProfile()
    {
        $user = auth()->user();

        // Busquem si hi ha un restaurant associat a l'usuari
        $restaurant = Restaurant::where('user_id', $user->id)->first();

        return Inertia::render('AccountConfig', [
            'user' => $user,
            'restaurant' => $restaurant, // Assegurar que passem el restaurant correcte
        ]);
    }


    public function showAccountConfig()
    {
        $user = auth()->user();
        return Inertia::render('Restaurants/AccountConfig', [
            'user' => $user,
        ]);
    }


}
