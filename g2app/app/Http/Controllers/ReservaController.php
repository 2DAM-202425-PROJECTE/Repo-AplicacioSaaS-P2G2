<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Models\Restaurant;
use App\Models\Ubicacio;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ReservaController extends Controller
{
    public function create()
    {
        $tipusCuinaOptions = Restaurant::$TIPUS_CUINA;
        $ubicacioOptions = Ubicacio::all(); // Get all locations for the dropdown
        return Inertia::render('Restaurants/Create', [
            'tipusCuinaOptions' => $tipusCuinaOptions,
            'ubicacioOptions' => $ubicacioOptions, // Pass the options to the view
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nom' => 'required|string|max:255',
            'descripcio' => 'required|string',
            'telefon' => 'required|string|max:20',
            'tipus_cuina' => 'required|string',
            'id_ubicacio' => 'required|exists:ubicacios,id_ubicacio',
            'hora_obertura' => 'required|date_format:H:i',
            'hora_tancament' => 'required|date_format:H:i',
        ]);

        Restaurant::create($validatedData);

        return redirect()->route('restaurants.index');
    }

}
