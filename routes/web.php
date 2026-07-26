<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\WeatherController;
use Illuminate\Support\Facades\Route;

// Home / Feed Principal de Locais
Route::get('/', [LocationController::class, 'index'])->name('home');
Route::get('/locations', [LocationController::class, 'index'])->name('locations.index');
Route::get('/locations/{location}', [LocationController::class, 'show'])->name('locations.show');
Route::get('/api/weather', [WeatherController::class, 'show'])->name('api.weather');

// Rotas de Autenticação Nativa (E-mail / Senha)
Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.perform');
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.perform');
});

// Rotas de Autenticação Socialite via Google
Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('login.google');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback']);

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

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
