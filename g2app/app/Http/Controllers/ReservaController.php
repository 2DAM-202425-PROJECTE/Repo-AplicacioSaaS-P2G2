<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ReservaController extends Controller
{
    public function create($restaurantId): Response
    {
        return Inertia::render('Reserves/Create', [
            'restaurantId' => $restaurantId,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_restaurant' => 'required|exists:restaurants,id',
            'telefon' => 'required|string|max:20',
            'data' => 'required|date',
            'hora' => 'required|date_format:H:i',
            'num_persones' => 'required|integer|min:1|max:20',
            'id_taula' => 'required|exists:taules,id',
            'estat' => 'required|string|max:255',
        ]);

        $reserva = Reserva::create($validated);

        return Inertia::render('Reserves/Show', [
            'reserva' => $reserva,
        ]);
    }

    public function index(Request $request): Response
    {
        $restaurantId = $request->query('restaurant_id');
        $reserves = Reserva::where('id_restaurant', $restaurantId)->get();

        return Inertia::render('Reserves/Index', [
            'reserves' => $reserves,
            'restaurantId' => $restaurantId,
        ]);
    }
}
