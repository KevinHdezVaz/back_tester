<?php

use TestController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppController;
use App\Http\Controllers\PaymentController;

 
// Rutas públicas (no requieren autenticación)
Route::get('/apps', [AppController::class, 'index']);
Route::post('/apps/{app}/test', [TestController::class, 'test']);

// Rutas protegidas (requieren autenticación)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/apps', [AppController::class, 'store']);
    Route::post('/apps/{app}/publish', [AppController::class, 'publish']);
    Route::post('/payment/{app}', [PaymentController::class, 'process']);
});

// Ruta de login (pública)
Route::post('/login', [AuthController::class, 'login']);