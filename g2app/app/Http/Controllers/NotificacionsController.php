<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class NotificacionsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $notificacions = $user->notifications()->orderBy('created_at', 'desc')->get();

        return Inertia::render('Notificacions/Index', [
            'notificacions' => $notificacions,
        ]);
    }
}
