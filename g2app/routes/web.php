<?php

use App\Http\Controllers\ReservaController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\TaulaController;
use Illuminate\Foundation\Application;
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

Route::get('/', [RestaurantController::class, 'index'])->name('home');
Route::get('/home', [HomeController::class, 'home'])->name('Home');
Route::get('/restaurants', [RestaurantController::class, 'index'])->name('restaurants.index');
Route::get('/restaurants/create', [RestaurantController::class, 'create'])->name('restaurants.create');
Route::get('/restaurants/{id}', [RestaurantController::class, 'show'])->name('restaurants.show');
Route::get('/reserves', [ReservaController::class, 'index'])->name('reserves.index');
Route::get('/taules', [TaulaController::class, 'index'])->name('taules.index');
Route::put('/restaurants/{restaurant}', [RestaurantController::class, 'update'])->name('restaurants.update');
Route::post('/restaurants', [RestaurantController::class, 'store'])->name('restaurants.store');

//});

