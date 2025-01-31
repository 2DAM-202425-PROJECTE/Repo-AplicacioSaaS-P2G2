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
        return Inertia::render('Restaurants/Create', [
            'tipusCuinaOptions' => Restaurant::$TIPUS_CUINA,
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nom' => 'required|string|max:255',
            'descripcio' => 'required|string',
            'telefon' => 'required|string|max:20',
            'tipus_cuina' => 'required|array',
            'hora_obertura' => 'required|date_format:H:i',
            'hora_tancament' => 'required|date_format:H:i',
        ]);

        Restaurant::create($request->all());

        return redirect()->route('restaurants.index');
    }

    public function edit(Restaurant $restaurant)
    {
        return Inertia::render('Restaurants/Edit', ['restaurant' => $restaurant]);
    }

    public function show($id): Response
    {
        $restaurant = Restaurant::findOrFail($id);
        $tipusCuinaOptions = Restaurant::$TIPUS_CUINA;
        return Inertia::render('Restaurants/Show', [
            'restaurant' => $restaurant,
            'tipusCuinaOptions' => $tipusCuinaOptions,
        ]);
    }

    public function update(Request $request, $id)
    {
        $restaurant = Restaurant::findOrFail($id);

        $validatedData = $request->validate([
            'nom' => 'required|string|max:255',
            'descripcio' => 'required|string',
            'telefon' => 'required|string|max:20',
            'tipus_cuina' => 'required|string', // Single string for cuisine type
            'hora_obertura' => 'required|date_format:H:i',
            'hora_tancament' => 'required|date_format:H:i',
        ]);


        $restaurant->update($validatedData);

    }


    public function destroy(Restaurant $restaurant)
    {
        $restaurant->delete();

        return redirect()->route('restaurants.index');
    }


}
