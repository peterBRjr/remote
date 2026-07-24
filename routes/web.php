<?php

use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

// Home / Feed Principal de Locais
Route::get('/', [LocationController::class, 'index'])->name('home');
Route::get('/locations', [LocationController::class, 'index'])->name('locations.index');
Route::get('/locations/{location}', [LocationController::class, 'show'])->name('locations.show');

// Rotas de Autenticação Socialite via Google
Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('login.google');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback']);
Route::post('/logout', [GoogleAuthController::class, 'logout'])->name('logout');

// Rotas de Demonstração / Dev Login (para testes rápidos)
Route::get('/login', [GoogleAuthController::class, 'redirect'])->name('login');

// Rotas Protegidas por Middleware Auth
Route::middleware(['auth'])->group(function () {
    // Cadastrar Novo Local
    Route::get('/locations/create/new', [LocationController::class, 'create'])->name('locations.create');
    Route::post('/locations', [LocationController::class, 'store'])->name('locations.store');

    // Avaliações
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');

    // Favoritos
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/locations/{location}/favorite', [FavoriteController::class, 'toggle'])->name('favorites.toggle');
});
