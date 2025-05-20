<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class NotificacionsController extends Controller
{
    public function index()
    {
        // Obtenim les notificacions de l'usuari autenticat
        $notificacions = Auth::user()->notifications->map(function ($notificacio) {
            return [
                'missatge' => $notificacio->data['missatge'] ?? 'Missatge desconegut',
                'data' => $notificacio->created_at->format('Y-m-d H:i'),
            ];
        });

        // Retornem la vista Inertia amb les dades
        return Inertia::render('Notificacions/Index', [
            'notificacions' => $notificacions,
        ]);
    }
}
