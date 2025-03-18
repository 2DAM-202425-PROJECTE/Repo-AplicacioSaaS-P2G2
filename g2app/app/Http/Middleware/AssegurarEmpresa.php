<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssegurarEmpresa
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check() || !Auth::user()->empresa) {
            return redirect()->route('dashboard')->with('error', 'No tens permisos per accedir aquí.');
        }
        return $next($request);
    }
}

