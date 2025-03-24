<?php

use App\Http\Controllers\Auth\GoogleController;
use Illuminate\Support\Facades\Route;

 

 Route::get('/', function () {
    return view('welcome');
});

// Rutas para el inicio de sesión con Google
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

// Ruta de login (para mostrar la vista de login en caso de error)
Route::get('login', function () {
    return view('auth.login');
})->name('login');

// Ruta protegida (ejemplo: dashboard para usuarios autenticados)
Route::middleware('auth')->group(function () {
    Route::get('dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});