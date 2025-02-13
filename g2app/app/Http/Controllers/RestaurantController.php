<?php
namespace App\Http\Controllers;
use App\Models\Restaurant;
use App\Models\Ubicacio;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class RestaurantController extends Controller
{
    public function index(): Response
    {
        $restaurants = Restaurant::all();
        $tipusCuinaOptions = Restaurant::$TIPUS_CUINA;
        $ubicacioOptions = Ubicacio::all();
        return Inertia::render('Restaurants/Index', [
            'restaurants' => $restaurants,
            'tipusCuinaOptions' => $tipusCuinaOptions,
            'ubicacioOptions' => $ubicacioOptions,
        ]);
    }

    public function create()
    {
        $tipusCuinaOptions = Restaurant::$TIPUS_CUINA;
        $ubicacioOptions = Ubicacio::all(); // Fetch all locations

        return Inertia::render('Restaurants/Create', [
            'tipusCuinaOptions' => $tipusCuinaOptions,
            'ubicacioOptions' => $ubicacioOptions, // Pass locations to the view
        ]);
    }



    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nom' => 'required|string|max:255',
            'descripcio' => 'required|string',
            'telefon' => 'required|string|max:20',
            'tipus_cuina' => 'required|string',
            'hora_obertura' => 'required|date_format:H:i',
            'hora_tancament' => 'required|date_format:H:i',
            'provincia' => 'required|string',
            'municipi' => 'required|string',
            'carrer' => 'required|string',
            'codi_postal' => 'required|string',
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
        $restaurant = Restaurant::with('ubicacio')->findOrFail($id);
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
