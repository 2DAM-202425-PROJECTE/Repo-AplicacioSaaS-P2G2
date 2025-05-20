<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Models\Restaurant;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;
use App\Notifications\EstatReservaCanviada;

class ReservaController extends Controller
{

    public function index($id)
    {
        $restaurant = Restaurant::with('municipio.provincia')->findOrFail($id);
        if (!Auth::user()->isEmpresa() || $restaurant->user_id !== Auth::id()) {
            return redirect()->route('restaurants.index')->with('error', 'No tens permís per editar aquest restaurant.');
        }
        $reserves = Reserva::where('id_restaurant', $id)
            ->orderBy('created_at', 'desc')
            ->orderBy('data', 'asc')
            ->orderBy('hora', 'asc')
            ->get();
        return Inertia::render('Restaurants/Manage', [
            'restaurant' => $restaurant,
            'activeTab' => 'reserves',
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
        $validatedData['created_at'] = Carbon::now();
        $reserva = new Reserva($validatedData);
        $reserva->id_usuari = Auth::id();
        $reserva->save();

        return redirect()->route('restaurants.show', ['id' => $request->id_restaurant])
            ->with(['flash' => ['message' => 'Reserva creada amb èxit!', 'type' => 'success']])
            ->withInput();
    }

    public function edit($id)
    {
        $reserva = Reserva::find($id);
        if (!Auth::user()->isEmpresa() || $reserva->restaurant->user_id !== Auth::id()) {
            return redirect()->route('restaurants.index')->with('error', 'No tens permís per editar aquesta reserva.');
        }

        if (!$reserva) {
            return Inertia::render('Error', ['message' => 'Reserva no trobada.']);
        }
        $reserva->terrassa = (bool) $reserva->terrassa;
        $reserva->hora = Carbon::parse($reserva->hora)->format('H:i');
        return Inertia::render('Reserves/Restaurant/EditModal', ['reserva' => $reserva]);
    }

    public function updateEstat(Request $request, $id): RedirectResponse
    {

        $reserva = Reserva::findOrFail($id);
        if (!Auth::user()->isEmpresa() || $reserva->restaurant->user_id !== Auth::id()) {
            return redirect()->route('restaurants.index')->with('error', 'No tens permís per editar aquesta reserva.');
        }

        $validatedData = $request->validate([
            'estat' => ['required', Rule::in([
                Reserva::PENDENT,
                Reserva::CONFIRMAT,
                Reserva::CANCELAT,
                Reserva::COMPLETAT,
            ])],
        ]);

        $reserva->update($validatedData);
        // Enviar notificació a l’usuari de la reserva
        $reserva->usuari->notify(new EstatReservaCanviada($reserva));
        return redirect()->back()->with('success', 'Status updated successfully');
    }

    public function update(Request $request, $id)
    {
        $reserva = Reserva::findOrFail($id);
        if (!Auth::user()->isEmpresa() || $reserva->restaurant->user_id !== Auth::id()) {
            return redirect()->route('restaurants.index')->with('error', 'No tens permís per editar aquesta reserva.');
        }

        $validatedData = $request->validate([
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
            'terrassa' => 'nullable|boolean',
        ]);

        $reserva->update($validatedData);
        // Enviar notificació a l’usuari de la reserva
        $reserva->usuari->notify(new EstatReservaCanviada($reserva));
        return redirect()->back()->with('success', 'Reservation updated successfully');
    }

    public function delete($id)
    {
        $reserva = Reserva::findOrFail($id);
        $restaurant = Restaurant::findOrFail($reserva->id_restaurant);
        if (!Auth::user()->isEmpresa() || $restaurant->user_id !== Auth::id()) {
            return redirect()->route('restaurants.index')->with('error', 'No tens permís per eliminar aquesta reserva.');
        }

        return Inertia::render('Reserves/Restaurant/DeleteModal', [
            'reservaId' => $reserva->id,
        ]);
    }

    public function destroy($id): RedirectResponse
    {

        $reserva = Reserva::findOrFail($id);
        if (!Auth::user()->isEmpresa() || $reserva->restaurant->user_id !== Auth::id()) {
            return redirect()->route('restaurants.index')->with('error', 'No tens permís per eliminar aquesta reserva.');
        }

        $reserva->delete();

        return redirect()->back()->with('success', 'Reserva eliminada amb èxit!');
    }
}
