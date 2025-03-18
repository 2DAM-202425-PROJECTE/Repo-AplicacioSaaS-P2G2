<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\TaulaController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Fer que la pàgina principal sigui el login
Route::get('/', function () {
    return Inertia::render('Login');
})->name('login');

// Permetre l'accés al registre sense estar autenticat
Route::get('/register', function () {
    return Inertia::render('Register');
})->name('register');

// Protegir totes les rutes perquè només siguin accessibles després del login
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return redirect()->route('restaurants.index');
    })->name('dashboard');

    // Rutes per als restaurants
    Route::get('/restaurants', [RestaurantController::class, 'index'])->name('restaurants.index');
    Route::get('/restaurants/create', [RestaurantController::class, 'create'])->name('restaurants.create');
    Route::get('/restaurants/{id}', [RestaurantController::class, 'show'])->name('restaurants.show');
    Route::put('/restaurants/{restaurant}', [RestaurantController::class, 'update'])->name('restaurants.update');
    Route::post('/restaurants', [RestaurantController::class, 'store'])->name('restaurants.store');

    // Rutes per a la gestió del perfil d'usuari
    Route::get('/perfil', [UserController::class, 'showProfile'])->name('user.profile');
    Route::get('/configuracio', [UserController::class, 'showAccountConfig'])->name('restaurants.AccountConfig');
    Route::get('/restaurant/crear', [RestaurantController::class, 'create'])->name('create-restaurant');
    Route::post('/restaurant', [RestaurantController::class, 'store']);
    Route::get('/restaurant/gestio', [RestaurantController::class, 'manageRestaurant'])->name('restaurant-management');
    Route::put('/restaurant/{id}', [RestaurantController::class, 'update']);

    // Rutes per a reserves i taules
    Route::post('/reserves', [ReservaController::class, 'store'])->name('reserves.store');
    Route::get('/taules', [TaulaController::class, 'index'])->name('taules.index');

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
