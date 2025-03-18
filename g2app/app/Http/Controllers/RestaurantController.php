<?php
namespace App\Http\Controllers;
use App\Models\Municipio;
use App\Models\Plat;
use App\Models\Provincia;
use App\Models\Restaurant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Auth;

class RestaurantController extends Controller
{
    public function index(): Response
    {
        $restaurants = Restaurant::with('municipio')->get();

        return Inertia::render('Restaurants/Index', [
            'restaurants' => $restaurants,
        ]);
    }

    public function create(Request $request)
    {
        // Crear restaurant només si l'usuari no té cap restaurant associat
        $tipusCuinaOptions = Restaurant::$TIPUS_CUINA;
        $provincias = Provincia::all();
        $municipios = [];

        if ($request->has('provincia_id')) {
            $provinciaId = $request->input('provincia_id');
            $municipios = Municipio::where('provincia_id', $provinciaId)->get();
        }

        return Inertia::render('Restaurants/Create', [
            'tipusCuinaOptions' => $tipusCuinaOptions,
            'provincias' => $provincias,
            'municipios' => $municipios,
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
            'municipio_id' => 'required|integer|exists:municipios,id',
            'carrer' => 'required|string',
            'plats' => 'nullable|array', // Add validation for plats
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


        // Crear el restaurant associat a l'usuari autenticat
        $validatedData['user_id'] = Auth::id();  // Afegir l'ID de l'usuari autenticat
        Restaurant::create($validatedData);

        if (isset($validatedData['plats']) && is_array($validatedData['plats'])) {
            foreach ($validatedData['plats'] as $platData) {
                Plat::create([
                    'nom' => $platData['nom'],
                    'descripcio' => $platData['descripcio'] ?? null,
                    'id_restaurant' => $restaurant->id,
                    'preu' => $platData['preu'],
                    'gluten' => $platData['gluten'] ?? true,
                    'lactics' => $platData['lactics'] ?? true,
                    'crustaci' => $platData['crustaci'] ?? true,
                    'ous' => $platData['ous'] ?? true,
                    'lupines' => $platData['lupines'] ?? true,
                    'mostassa' => $platData['mostassa'] ?? true,
                    'cacahuats' => $platData['cacahuats'] ?? true,
                    'soja' => $platData['soja'] ?? true,
                    'vegetaria' => $platData['vegetaria'] ?? true,
                    'vega' => $platData['vega'] ?? true,
                    'carn_vermella' => $platData['carn_vermella'] ?? true,
                    'kosher' => $platData['kosher'] ?? true,
                    'halal' => $platData['halal'] ?? true,
                    'keto' => $platData['keto'] ?? true,
                ]);
            }
        }

        return redirect()->route('restaurants.index');
    }

    public function show($id): Response
    {
        $restaurant = Restaurant::with(['municipio.provincia', 'plats'])->findOrFail($id);

        return Inertia::render('Restaurants/Show', [
            'restaurant' => $restaurant,
        ]);
    }

    public function edit($id): Response
    {
        // Comprovem que l'usuari té permís per editar aquest restaurant
        if ($restaurant->user_id !== Auth::id()) {
            return redirect()->route('restaurants.index')->with('error', 'No tens permís per editar aquest restaurant.');
        }
      
        $restaurant = Restaurant::with('municipio.provincia')->findOrFail($id);
        $tipusCuinaOptions = Restaurant::$TIPUS_CUINA;
        $provincias = Provincia::all();
        $municipios = Municipio::where('provincia_id', $restaurant->municipio->provincia_id)->get();

        return Inertia::render('Restaurants/AdminEdit', [
            'restaurant' => $restaurant,
            'tipusCuinaOptions' => $tipusCuinaOptions,
            'provincias' => $provincias,
            'municipios' => $municipios,
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
            'municipio_id' => 'required|integer|exists:municipios,id',
            'carrer' => 'required|string',

        ]);

        $restaurant->update($validatedData);
        return redirect()->route('restaurants.show', ['id' => $restaurant->id]);
    }

    public function getMunicipios(Request $request): JsonResponse
    {
        $provinciaId = $request->input('provincia_id');
        $municipios = Municipio::where('provincia_id', $provinciaId)->get();
        return response()->json($municipios);
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
