<?php

use App\Http\Controllers\Controller;
use App\Http\Controllers\HomeController;
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
Route::get('/home', [HomeController::class, 'home'])->name('Home');
Route::get('/restaurants', [RestaurantController::class, 'index'])->name('restaurants.index');
Route::get('/restaurants/create', [RestaurantController::class, 'create'])->name('restaurants.create');
Route::get('/restaurants/{id}', [RestaurantController::class, 'show'])->name('restaurants.show');
Route::get('/reserves', [ReservaController::class, 'index'])->name('reserves.index');
Route::get('/taules', [TaulaController::class, 'index'])->name('taules.index');
Route::put('/restaurants/{restaurant}', [RestaurantController::class, 'update'])->name('restaurants.update');
Route::post('/restaurants', [RestaurantController::class, 'store'])->name('restaurants.store');


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});
