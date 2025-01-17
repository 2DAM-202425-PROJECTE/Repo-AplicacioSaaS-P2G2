<?php
namespace App\Http\Controllers;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class RestaurantController extends Controller
{
    public function index(): Response
    {
        $restaurants = Restaurant::all();
        return Inertia::render('Restaurants/Index', ['restaurants' => $restaurants]);
    }

    public function create()
    {
        return Inertia::render('Restaurants/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_ubicacio' => 'exists:ubicacions,id_ubicacio',
            'nom' => 'required|string|max:255',
            'horari' => 'required|string|max:255',
            'descripcio' => 'required|string',
            'telefon' => 'required|string|max:20',
            'tipus_cuina' => 'required|array',
        ]);

        Restaurant::create($request->all());

        return redirect()->route('restaurants.index');
    }

    public function edit(Restaurant $restaurant)
    {
        return Inertia::render('Restaurants/Edit', ['restaurant' => $restaurant]);
    }

    public function update(Request $request, Restaurant $restaurant)
    {
        $request->validate([
            'id_ubicacio' => 'exists:ubicacions,id_ubicacio',
            'nom' => 'required|string|max:255',
            'horari' => 'required|string|max:255',
            'descripcio' => 'required|string',
            'telefon' => 'required|string|max:20',
            'tipus_cuina' => 'required|array',
        ]);

        $restaurant->update($request->all());

        return redirect()->route('restaurants.index');
    }

    public function destroy(Restaurant $restaurant)
    {
        $restaurant->delete();

        return redirect()->route('restaurants.index');
    }
}
