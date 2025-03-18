<?php
namespace App\Http\Controllers;

use App\Models\Restaurant;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Auth;

class RestaurantController extends Controller
{
    public function index(): Response
    {
        $restaurants = Restaurant::all();
        $tipusCuinaOptions = Restaurant::$TIPUS_CUINA;

        return Inertia::render('Restaurants/Index', [
            'restaurants' => $restaurants,
            'tipusCuinaOptions' => $tipusCuinaOptions,
        ]);
    }

    public function create()
    {
        // Crear restaurant només si l'usuari no té cap restaurant associat
        $tipusCuinaOptions = Restaurant::$TIPUS_CUINA;
        return Inertia::render('Restaurants/Create', [
            'tipusCuinaOptions' => $tipusCuinaOptions,
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
        ]);

        // Crear el restaurant associat a l'usuari autenticat
        $validatedData['user_id'] = Auth::id();  // Afegir l'ID de l'usuari autenticat
        Restaurant::create($validatedData);

        return redirect()->route('restaurants.index');
    }

    public function edit(Restaurant $restaurant)
    {
        // Comprovem que l'usuari té permís per editar aquest restaurant
        if ($restaurant->user_id !== Auth::id()) {
            return redirect()->route('restaurants.index')->with('error', 'No tens permís per editar aquest restaurant.');
        }
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

        // Comprovem que l'usuari té permís per editar aquest restaurant
        if ($restaurant->user_id !== Auth::id()) {
            return redirect()->route('restaurants.index')->with('error', 'No tens permís per actualitzar aquest restaurant.');
        }

        $validatedData = $request->validate([
            'nom' => 'required|string|max:255',
            'descripcio' => 'required|string',
            'telefon' => 'required|string|max:20',
            'tipus_cuina' => 'required|string',
            'hora_obertura' => 'required|date_format:H:i',
            'hora_tancament' => 'required|date_format:H:i',
            'user_id' => auth()->id(),
        ]);

        $restaurant->update($validatedData);

        return redirect()->route('restaurants.index');
    }

    public function destroy(Restaurant $restaurant)
    {
        // Comprovem que l'usuari té permís per eliminar aquest restaurant
        if ($restaurant->user_id !== Auth::id()) {
            return redirect()->route('restaurants.index')->with('error', 'No tens permís per eliminar aquest restaurant.');
        }

        $restaurant->delete();

        return redirect()->route('restaurants.index');
    }

    // Funció per mostrar la gestió
    public function manageRestaurant()
    {
        // Comprovem si l'usuari té un restaurant associat
        $restaurant = Restaurant::where('user_id', Auth::id())->first();

        if ($restaurant) {
            // Si l'usuari té un restaurant pàgina de gestió del restaurant
            return redirect()->route('restaurant-management', ['id' => $restaurant->id]);
        }

        // Si no té cap restaurant associat pàgina per fer un nou
        return Inertia::render('Restaurants/Create');
    }

    // Funció per crear restaurant només si l'usuari no en té cap
    public function createRestaurantForUser()
    {
        // Si l'usuari ja té un restaurant el redirigim a la pag de gestió
        $restaurant = Restaurant::where('user_id', Auth::id())->first();
        if ($restaurant) {
            return redirect()->route('restaurant-management', ['id' => $restaurant->id]);
        }

        return Inertia::render('Restaurants/Create');
    }
}
