<?php

use App\Http\Controllers\ReservaController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\TaulaController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
/*
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});
*/
//Route::resource('restaurants', RestaurantController::class);
//Route::get('/restaurants', [RestaurantController::class, 'index'])->middleware(['auth', 'verified'])->name('restaurants.index');


Route::get('/', [RestaurantController::class, 'index'])->name('home');
Route::get('/restaurants', [RestaurantController::class, 'index'])->name('restaurants.index');
Route::get('/restaurants/create', [RestaurantController::class, 'create'])->name('restaurants.create');
Route::get('/restaurants/{id}', [RestaurantController::class, 'show'])->name('restaurants.show');
Route::post('/reserves', [ReservaController::class, 'store'])->name('reserves.store');
Route::get('/taules', [TaulaController::class, 'index'])->name('taules.index');
Route::put('/restaurants/{restaurant}', [RestaurantController::class, 'update'])->name('restaurants.update');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});
