<?php

use App\Http\Controllers\ReservaController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\TaulaController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

//Fer que la pàgina principal sigui el login
Route::get('/', function () {
    return Inertia::render('Login');
})->name('login');

//Permetre l'accés al registre sense estar autenticat
Route::get('/register', function () {
    return Inertia::render('Register');
})->name('register');

//Protegir totes les rutes perquè només siguin accessibles després del login

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return redirect()->route('restaurants.index');
    })->name('dashboard');

    Route::get('/restaurants', [RestaurantController::class, 'index'])->name('restaurants.index');
    Route::get('/restaurants/create', [RestaurantController::class, 'create'])->name('restaurants.create');
    Route::get('/restaurants/{id}', [RestaurantController::class, 'show'])->name('restaurants.show');
    Route::post('/reserves', [ReservaController::class, 'store'])->name('reserves.store');
    Route::get('/taules', [TaulaController::class, 'index'])->name('taules.index');
    Route::put('/restaurants/{restaurant}', [RestaurantController::class, 'update'])->name('restaurants.update');
    Route::post('/restaurants', [RestaurantController::class, 'store'])->name('restaurants.store');
    Route::get('/get/municipios', [RestaurantController::class, 'getMunicipios'])->name('get.municipios');
    Route::get('/restaurants/{id}/reserves', [ReservaController::class, 'index'])->name('restaurant.reserves');
});

