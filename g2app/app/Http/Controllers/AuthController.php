<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!Auth::attempt($credentials)) {
            return Redirect::back()->withErrors(['email' => 'Credencials incorrectes']);
        }

        $request->session()->regenerate();
        return Redirect::route('dashboard');
    }

    public function register(Request $request)
    {
        // Converteix el valor d'empresa a boolean
        $empresa = $request->input('empresa') ? true : false;

        // Validació de les dades
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|string|min:8|confirmed',
            //'empresa' => 'required|boolean',
        ]);

        // Crear l'usuari amb les dades
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'empresa' => $empresa, // Assignem el valor bool per a que no pete
        ]);

        // Iniciar la sessió de l'usuari
        Auth::login($user);

        // Redirigir a la vista
        return Redirect::route('dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::route('login');
    }
}
