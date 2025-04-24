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

class ReservaUserController
{
    public function index()
    {

        $userReserves = Reserva::where('id_usuari', Auth::id())
            ->with('restaurant:id,nom')
            ->orderBy('created_at', 'desc')
            ->orderBy('data', 'asc')
            ->orderBy('hora', 'asc')
            ->get();

        return Inertia::render('Reserves/User/Index', [
            'reserves' => $userReserves,
        ]);
    }

    public function edit($id)
    {
        $reserva = Reserva::find($id);
        if ($reserva->id_usuari !== Auth::id()) {
            return redirect()->route('restaurants.index')->with('error', 'No tens permís per editar aquesta reserva.');
        }

        if (!$reserva) {
            return Inertia::render('Error', ['message' => 'Reserva no trobada.']);
        }
        $reserva->terrassa = (bool) $reserva->terrassa;
        $reserva->hora = Carbon::parse($reserva->hora)->format('H:i');
        return Inertia::render('Reserves/User/EditModal', ['reserva' => $reserva]);
    }

    public function update(Request $request, $id)
    {
        $reserva = Reserva::findOrFail($id);
        if ($reserva->id_usuari !== Auth::id()) {
            return redirect()->route('restaurants.index')->with('error', 'No tens permís per editar aquesta reserva.');
        }

        $validatedData = $request->validate([
            'telefon' => 'required|string',
            'data' => 'required|date',
            'hora' => 'required|date_format:H:i',
            'num_persones' => 'required|integer|min:1',
            'solicituds' => 'nullable|string',
            'terrassa' => 'nullable|boolean',
        ]);

        $reserva->update($validatedData);
        return redirect()->back()->with('success', 'Reservation updated successfully');
    }


    public function cancel($id)
    {
        $reserva = Reserva::findOrFail($id);
        if ($reserva->id_usuari !== Auth::id()) {
            return redirect()->route('restaurants.index')->with('error', 'No tens permís per editar aquesta reserva.');
        }

        return Inertia::render('Reserves/User/CancelModal', [
            'reservaId' => $reserva->id,
        ]);
    }

    public function cancelConfirm($id): RedirectResponse
    {

        $reserva = Reserva::findOrFail($id);
        if ($reserva->id_usuari !== Auth::id()) {
            return redirect()->route('restaurants.index')->with('error', 'No tens permís per editar aquesta reserva.');
        }

        $reserva->update(['estat' => 2]);

        return redirect()->back()->with('success', 'Status updated successfully');
    }


}
