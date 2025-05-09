<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use App\Models\RestaurantFavorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RestaurantFavoriteController extends Controller
{
    public function toggle(Restaurant $restaurant)
    {
        $user = Auth::user();

        $existingFavorite = RestaurantFavorite::where('user_id', $user->id)
            ->where('restaurant_id', $restaurant->id)
            ->first();

        if ($existingFavorite) {
            $existingFavorite->delete();
            $isFavorite = false;
        } else {
            RestaurantFavorite::create([
                'user_id' => $user->id,
                'restaurant_id' => $restaurant->id,
            ]);
            $isFavorite = true;
        }

        return response()->json(['isFavorite' => $isFavorite]);
    }

    public function isFavorite(Restaurant $restaurant)
    {
        $user = Auth::user();
        $isFavorite = $user->favoriteRestaurants()->where('restaurant_id', $restaurant->id)->exists();

        return response()->json(['isFavorite' => $isFavorite]);
    }
}
