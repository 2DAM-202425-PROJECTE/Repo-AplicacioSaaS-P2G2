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
    Route::post('/restaurants', [RestaurantController::class, 'store'])->name('restaurants.store');
    Route::get('/restaurants/{id}/edit', [RestaurantController::class, 'edit'])->name('restaurants.edit');
    Route::get('/get/municipios', [RestaurantController::class, 'getMunicipios'])->name('get.municipios');

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
    Route::get('/restaurants/{id}/reserves', [ReservaController::class, 'index'])->name('restaurant.reserves');
    Route::put('/reserves/{id}', [ReservaController::class, 'update'])->name('reserves.update');
    Route::get('/reserves/{id}/edit', [ReservaController::class, 'edit'])->name('reserves.edit');
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
