<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Models\Restaurant;
use App\Models\Ubicacio;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class ReservaController extends Controller
{
    public function store(Request $request): void
    {
        $validatedData = $request->validate([
            'id_taula' => 'required|exists:taules,id',
            'id_restaurant' => 'required|exists:restaurants,id',
            'telefon' => 'required|string',
            'data' => 'required|date',
            'hora' => 'required|date_format:H:i',
            'num_persones' => 'required|integer|min:1',
            'estat' => ['required', Rule::in([
                Reserva::PENDENT,
                Reserva::CONFIRMAT,
                Reserva::CANCELAT,
                Reserva::COMPLETAT,
            ])],
            'solicituds' => 'nullable|string',
        ]);

        $reserva = new Reserva($validatedData);
        $reserva->save();
    }

}
