<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductoApiController;
use App\Http\Controllers\Api\CarritoApiController;

// Rutas públicas
Route::get('/productos', [ProductoApiController::class, 'index']);
Route::get('/productos/{id}', [ProductoApiController::class, 'show']);

// Rutas protegidas con Sanctum (requiere autenticación)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/carrito/{id}', [CarritoApiController::class, 'agregar']);
    Route::get('/carrito', [CarritoApiController::class, 'mostrar']);
    Route::delete('/carrito/{id}', [CarritoApiController::class, 'eliminar']);
});
