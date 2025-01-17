<?php

use App\Http\Controllers\RestaurantController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

//Route::resource('restaurants', RestaurantController::class);
//Route::get('/restaurants', [RestaurantController::class, 'index'])->middleware(['auth', 'verified'])->name('restaurants.index');
Route::get('/restaurants', [RestaurantController::class, 'index'])->name('restaurants.index');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});
