<?php
namespace App\Http\Controllers;
use App\Models\Municipio;
use App\Models\Plat;
use App\Models\Provincia;
use App\Models\Restaurant;
use Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Storage;

class RestaurantController extends Controller
{
    public function index(): Response
    {
        $restaurants = Restaurant::with('municipio')->get();
        $plats = Plat::all(); // Obtenim tots els plats


        return inertia('Restaurants/Index', [
            'restaurants' => $restaurants,
            'plats' => $plats, // Enviem els plats a Vue
        ]);
    }

    public function create(Request $request)
    {
        $user = Auth::user();

        // Comprovar si l'usuari ja té un restaurant
        if (Restaurant::where('user_id', $user->id)->exists()) {
            return redirect()->route('restaurants.index')->with('error', 'Només pots tenir un restaurant.');
        }

        if (!$user->isEmpresa()) {
            return redirect()->route('restaurants.index')->with('error', 'Només les empreses poden crear restaurants.');
        }

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
        if (!Auth::user()->isEmpresa()) {
            return redirect()->route('restaurants.index')->with('error', 'Només les empreses poden crear restaurants.');
        }
        $validatedData = $request->validate([
            'nom' => 'required|string|max:255',
            'descripcio' => 'required|string',
            'telefon' => 'required|string|max:20',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'tipus_cuina' => 'required|string',
            'hora_obertura' => 'required|date_format:H:i',
            'hora_tancament' => 'required|date_format:H:i',
            'municipio_id' => 'required|integer|exists:municipios,id',
            'carrer' => 'required|string',

        ]);

        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('profile_images', 'public');
            $validatedData['profile_image'] = $path;
        }
        $validatedData['user_id'] = Auth::id();
        Restaurant::create($validatedData);



        return redirect()->route('restaurants.index');
    }

    public function show($id): Response
    {
        $restaurant = Restaurant::with(['municipio.provincia', 'plats'])->findOrFail($id);

        return Inertia::render('Restaurants/Show', [
            'restaurant' => $restaurant,
        ]);
    }

    public function edit($id)
    {
        $restaurant = Restaurant::with('municipio.provincia')->findOrFail($id);

        if (!Auth::user()->isEmpresa() || $restaurant->user_id !== Auth::id()) {
            return redirect()->route('restaurants.index')->with('error', 'No tens permís per editar aquest restaurant.');
        }

        $tipusCuinaOptions = Restaurant::$TIPUS_CUINA;
        $provincias = Provincia::all();
        $municipios = Municipio::where('provincia_id', $restaurant->municipio->provincia_id)->get();

        return Inertia::render('Restaurants/Manage', [
            'restaurant' => $restaurant,
            'activeTab' => 'edit',
            'tipusCuinaOptions' => $tipusCuinaOptions,
            'provincias' => $provincias,
            'municipios' => $municipios,
        ]);
    }

    public function update(Request $request, $id)
    {
        $restaurant = Restaurant::findOrFail($id);

        // Comprovem que l'usuari té permís per editar aquest restaurant
        if (!Auth::user()->isEmpresa() || $restaurant->user_id !== Auth::id()) {
            return redirect()->route('restaurants.index')->with('error', 'No tens permís per actualitzar aquest restaurant.');
        }

        $validatedData = $request->validate([
            'nom' => 'required|string|max:255',
            'descripcio' => 'required|string',
            'telefon' => 'required|string|max:20',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'tipus_cuina' => 'required|string',
            'hora_obertura' => 'required|date_format:H:i',
            'hora_tancament' => 'required|date_format:H:i',
            'municipio_id' => 'required|integer|exists:municipios,id',
            'carrer' => 'required|string',
        ]);

        $dataToUpdate = $request->only(['nom', 'descripcio', 'telefon', 'tipus_cuina', 'hora_obertura', 'hora_tancament', 'municipio_id', 'carrer']);

        if ($request->hasFile('profile_image')) {
            if ($restaurant->profile_image) {
                Storage::disk('public')->delete($restaurant->profile_image);
            }
            $path = $request->file('profile_image')->store('profile_images', 'public');
            $dataToUpdate['profile_image'] = $path;
        }

        $restaurant->update($dataToUpdate);

        return redirect()->route('restaurants.edit', ['id' => $restaurant->id]);
    }

    public function getMunicipios(Request $request): JsonResponse
    {
        $provinciaId = $request->input('provincia_id');
        $municipios = Municipio::where('provincia_id', $provinciaId)->get();
        return response()->json($municipios);
    }

    public function destroy($id)
    {
        $restaurant = Restaurant::findOrFail($id);
        // Comprovem que l'usuari té permís per eliminar aquest restaurant
        if (!Auth::user()->isEmpresa() || $restaurant->user_id !== Auth::id()) {
            return redirect()->route('restaurants.index')->with('error', 'No tens permís per eliminar aquest restaurant.');
        }


        $restaurant->delete();
        return redirect()->route('restaurants.index');
    }

    // Funció per mostrar la gestió
    public function manageRestaurant($id)
    {
        $restaurant = Restaurant::with(['municipio.provincia'])->findOrFail($id);  // Eager load relationships

        return Inertia::render('Restaurants/Manage', [
            'restaurant' => $restaurant
        ]);
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
