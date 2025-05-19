<?php

namespace App\Http\Controllers;

use Auth;
use DB;
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
        $restaurant = Restaurant::where('user_id', $user->id)->first();
        return Inertia::render('Restaurants/AccountConfig', [
            'user' => $user,
            'restaurant' => $restaurant,
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
        $restaurant = Restaurant::where('user_id', $userId)->first();

        return response()->json([
            'restaurant' => $restaurant
        ]);
    }


    public function destroy(string $id)
    {
        // Comprovar si l'usuari autenticat és el mateix que l'usuari a eliminar
        if (Auth::id() !== (int)$id) {
            return redirect()->route('home')->with('error', 'No tens permís per eliminar aquest usuari.');
        }

        DB::transaction(function () use ($id) {
            $user = User::findOrFail($id);

            // Buscar el restaurant associat
            $restaurant = Restaurant::where('user_id', $user->id)->first();

            // Si existeix, cridar a RestaurantsController@destroy
            if ($restaurant) {
                $restaurantsController = app(RestaurantController::class);
                $restaurantsController->destroy($restaurant->id);
            }

            // Finalment eliminar l’usuari
            $user->delete();
        });

        return redirect()->route('login')->with('success', 'User eliminat correctament.');
    }

    public function favorites()
    {
        $user = Auth::user();
        $restaurants = $user->favoriteRestaurants;

        return response()->json([
            'restaurants' => $restaurants,
        ]);
    }


}
