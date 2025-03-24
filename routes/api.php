<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Auth\GoogleController;

 
// Rutas públicas (no requieren autenticación)
Route::get('/apps', [AppController::class, 'index']);
Route::post('/apps/{app}/test', [TestController::class, 'test']);
Route::post('/auth/google', [GoogleController::class, 'handleGoogleAuth']);

// Rutas protegidas (requieren autenticación)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/apps', [AppController::class, 'store']);
    Route::post('/apps/{app}/publish', [AppController::class, 'publish']);
    Route::post('/payment/{app}', [PaymentController::class, 'process']);
    Route::get('/user', [AuthController::class, 'getUser']);  

});

Route::get('/all-apps', [AppController::class, 'allApps']);
Route::get('/apps/{id}', [AppController::class, 'show']);

// Ruta de login (pública)
Route::post('/login', [AuthController::class, 'login']);  
Route::post('/register', [AuthController::class, 'register']);