<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Models\Restaurant;
use App\Models\Taula;
use Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class ReservaController extends Controller
{

    public function index($id)
    {
        $restaurant = Restaurant::findOrFail($id);
        $reserves = Reserva::where('id_restaurant', $id)->get();
        if (!Auth::user()->isEmpresa()) {
            return redirect()->route('restaurants.index')->with('error', 'No tens permís per editar aquest restaurant.');
        }
        return Inertia::render('Reserves/Index', [
            'restaurant' => $restaurant,
            'reserves' => $reserves
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
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
            'terrassa' => 'nullable|boolean',
        ]);

        $taules = Taula::where('id_restaurant', $validatedData['id_restaurant'])->get();

        // Randomly select a taula
        $randomTaula = $taules->random();

        // Add the randomly selected id_taula to the validated data
        $validatedData['id_taula'] = $randomTaula->id;

        $reserva = new Reserva($validatedData);
        $reserva->save();

        return redirect()->route('restaurants.show', ['id' => $request->id_restaurant])
            ->with(['flash' => ['message' => 'Reserva creada amb èxit!', 'type' => 'success']])
            ->withInput();
    }

    public function update(Request $request, $id)
    {
        $reserva = Reserva::findOrFail($id);

        $validatedData = $request->validate([
            'estat' => ['required', Rule::in([
                Reserva::PENDENT,
                Reserva::CONFIRMAT,
                Reserva::CANCELAT,
                Reserva::COMPLETAT,
            ])],
        ]);

        $reserva->update($validatedData);

        return redirect()->back()->with('success', 'Status updated successfully');
    }
}
