<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Models\Restaurant;
use Auth;
use Inertia\Inertia;

class ReservaUserController
{
    public function index()
    {

        $userReserves = Reserva::where('user_id', Auth::id())
            ->with('restaurant:id,nom') // Carrega el nom del restaurant per mostrar-lo
            ->orderBy('data', 'asc')
            ->orderBy('hora', 'asc')
            ->get();

        return Inertia::render('Reserves/IndexUser', [
            'reserves' => $userReserves,
        ]);
    }

}
