<?php

namespace App\Http\Controllers;

use App\Models\Plat;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PlatController extends Controller
{
    public function index($restaurantId)
    {
        $restaurant = Restaurant::findOrFail($restaurantId);

        if (!$restaurant) {
            return redirect()->route('restaurants.create')->with('error', 'Si us plau, crea un restaurant per gestionar la carta.');
        }
        $plats = $restaurant->plats;

        return Inertia::render('Restaurants/Plats', [
            'restaurant' => $restaurant,
            'plats' => $plats,
        ]);
    }

    public function update(Request $request, $restaurantId)
    {
        $restaurant = Restaurant::findOrFail($restaurantId);

        $validatedData = $request->validate([
            'plats.*.id' => 'nullable|exists:plats,id',
            'plats.*.nom' => 'required|string',
            'plats.*.descripcio' => 'nullable|string',
            'plats.*.preu' => 'required|numeric',
            'plats.*.gluten' => 'nullable|boolean',
            'plats.*.lactics' => 'nullable|boolean',
            'plats.*.crustaci' => 'nullable|boolean',
            'plats.*.ous' => 'nullable|boolean',
            'plats.*.lupines' => 'nullable|boolean',
            'plats.*.mostassa' => 'nullable|boolean',
            'plats.*.cacahuats' => 'nullable|boolean',
            'plats.*.soja' => 'nullable|boolean',
            'plats.*.vegetaria' => 'nullable|boolean',
            'plats.*.vega' => 'nullable|boolean',
            'plats.*.carn_vermella' => 'nullable|boolean',
            'plats.*.kosher' => 'nullable|boolean',
            'plats.*.halal' => 'nullable|boolean',
            'plats.*.keto' => 'nullable|boolean',
        ]);

        if (isset($validatedData['plats'])) {
            $updatedPlatIds = [];

            foreach ($validatedData['plats'] as $platData) {
                if (isset($platData['id'])) {
                    // Actualitzar plat existent
                    Plat::find($platData['id'])->update($platData);
                    $updatedPlatIds[] = $platData['id'];
                } else {
                    // Crear nou plat
                    $newPlat = Plat::create(array_merge($platData, ['id_restaurant' => $restaurant->id]));
                    $updatedPlatIds[] = $newPlat->id;
                }
            }

            // Eliminar plats que no estan presents a la llista actualitzada
            $platsToDelete = $restaurant->plats->pluck('id')->diff($updatedPlatIds)->toArray();
            Plat::whereIn('id', $platsToDelete)->delete();
        } else {
            // Si no hi ha plats a la petició, eliminar tots els plats del restaurant
            Plat::where('id_restaurant', $restaurant->id)->delete();
        }

        return redirect()->route('restaurants.show', ['id' => $restaurant->id]);
    }
}
