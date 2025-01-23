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
        $request->validate([
            'id_restaurant' => 'required|exists:restaurants,id',
            'telefon' => 'required|string|max:20',
            'data' => 'required|date',
            'hora' => 'required|date_format:H:i',
            'num_persones' => 'required|integer|min:1',
            'estat' => 'required|string|max:255',
        ]);

        Reserva::create($request->all());

        return redirect()->route('restaurants.show', $request->id_restaurant)
            ->with('success', 'Reservation created successfully.');
    }
}
