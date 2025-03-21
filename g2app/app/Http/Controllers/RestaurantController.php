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

        $restaurant = Restaurant::create($validatedData);

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
        $restaurant = Restaurant::with('municipio.provincia', 'plats')->findOrFail($id);
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

        $validatedData = $request->validate([
            'nom' => 'required|string|max:255',
            'descripcio' => 'required|string',
            'telefon' => 'required|string|max:20',
            'tipus_cuina' => 'required|string',
            'hora_obertura' => 'required|date_format:H:i',
            'hora_tancament' => 'required|date_format:H:i',
            'municipio_id' => 'required|integer|exists:municipios,id',
            'carrer' => 'required|string',
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

        $restaurant->update($validatedData);

        if (isset($validatedData['plats'])) {
            $updatedPlatIds = []; // Array per guardar els IDs dels plats actualitzats

            foreach ($validatedData['plats'] as $platData) {
                if (isset($platData['id'])) {
                    // Actualitzar plat existent
                    Plat::find($platData['id'])->update($platData);
                    $updatedPlatIds[] = $platData['id']; // Afegir ID a l'array
                } else {
                    // Crear nou plat
                    $newPlat = Plat::create(array_merge($platData, ['id_restaurant' => $restaurant->id]));
                    $updatedPlatIds[] = $newPlat->id; // Afegir ID a l'array
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

    public function getMunicipios(Request $request): JsonResponse
    {
        $provinciaId = $request->input('provincia_id');
        $municipios = Municipio::where('provincia_id', $provinciaId)->get();
        return response()->json($municipios);
    }

    public function destroy(Restaurant $restaurant)
    {
        $restaurant->delete();
        return redirect()->route('restaurants.index');
    }
}
