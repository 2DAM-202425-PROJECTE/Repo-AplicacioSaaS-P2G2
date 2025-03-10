<?php
namespace App\Http\Controllers;
use App\Models\Municipio;
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
        ]);

        Restaurant::create($validatedData);

        return redirect()->route('restaurants.index');
    }

    public function show($id): Response
    {
        $restaurant = Restaurant::with('municipio.provincia')->findOrFail($id);

        return Inertia::render('Restaurants/Show', [
            'restaurant' => $restaurant,
        ]);
    }

    public function edit($id): Response
    {
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

        $validatedData = $request->validate([
            'nom' => 'required|string|max:255',
            'descripcio' => 'required|string',
            'telefon' => 'required|string|max:20',
            'tipus_cuina' => 'required|string',
            'hora_obertura' => 'required|date_format:H:i',
            'hora_tancament' => 'required|date_format:H:i',
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
        $restaurant->delete();
        return redirect()->route('restaurants.index');
    }
}
