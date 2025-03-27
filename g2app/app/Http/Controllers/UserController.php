<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Restaurant;
use Illuminate\Support\Facades\Hash;
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

    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:users,email,' . $user->id,
        ]);

        $user->update($request->only(['name', 'email']));

        return back()->with('success', 'Dades actualitzades correctament.');
    }

    public function updatePassword(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'min:8', 'confirmed'],
        ]);

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Contrasenya actualitzada correctament.');
    }

    public function getUserRestaurant($userId)
    {
        $user = User::find($userId);
        $restaurant = $user->restaurant;

        return response()->json([
            'restaurant' => $restaurant
        ]);
    }



}
