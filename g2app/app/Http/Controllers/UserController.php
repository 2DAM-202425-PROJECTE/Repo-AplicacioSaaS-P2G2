<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Restaurant;
use Inertia\Inertia;

class UserController extends Controller
{

    public function showProfile()
    {
        $user = auth()->user();
        $restaurant = $user->restaurant; // Accedeix al restaurant associat

        return view('user.profile', compact('user', 'restaurant'));
    }

    public function showAccountConfig()
    {
        $user = auth()->user();
        return Inertia::render('Restaurants/AccountConfig', [
            'user' => $user,
        ]);
    }


}
