<?php

namespace App\Http\Controllers;

use App\Models\Plat;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PlatController extends Controller
{
    public function index($restaurant)
    {
        $restaurant = Restaurant::with('municipio.provincia')->findOrFail($restaurant);

        if (!$restaurant) {
            return redirect()->route('restaurants.create')->with('error', 'Si us plau, crea un restaurant per gestionar la carta.');
        }
        $plats = $restaurant->plats;

        return Inertia::render('Restaurants/Manage', [
            'restaurant' => $restaurant,
            'activeTab' => 'plats',
        ]);
    }

    public function store(Request $request, $restaurantId)
    {
        $restaurant = Restaurant::findOrFail($restaurantId);

        $validatedData = $request->validate([
            'nom' => 'required|string',
            'descripcio' => 'nullable|string',
            'preu' => 'required|numeric',
            'gluten' => 'nullable|boolean',
            'lactics' => 'nullable|boolean',
            'crustaci' => 'nullable|boolean',
            'ous' => 'nullable|boolean',
            'lupines' => 'nullable|boolean',
            'mostassa' => 'nullable|boolean',
            'cacahuats' => 'nullable|boolean',
            'soja' => 'nullable|boolean',
            'vegetaria' => 'nullable|boolean',
            'vega' => 'nullable|boolean',
            'carn_vermella' => 'nullable|boolean',
            'kosher' => 'nullable|boolean',
            'halal' => 'nullable|boolean',
            'keto' => 'nullable|boolean',
        ]);

        $plat = new Plat(array_merge($validatedData, ['id_restaurant' => $restaurant->id]));
        $plat->save();

        return response()->json($plat);
    }

    public function destroy($restaurantId, $platId)
    {
        $plat = Plat::findOrFail($platId);
        $plat->delete();

        return response()->json(['success' => true]);
    }
}
