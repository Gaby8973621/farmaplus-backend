<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FrontController;
use App\Http\Controllers\Api\Admin\UserController;
use App\Http\Controllers\Api\Admin\CategoriaController;
use App\Http\Controllers\Api\ProductoApiController;
use App\Http\Controllers\Api\CarritoApiController;
//use App\Http\Controllers\Api\ProductoApiController as ProductoClient;


// Agrupamos todas las rutas con el prefijo v1
Route::prefix('v1')->group(function () {

    ////////// RUTAS PÚBLICAS //////////

    // Mostrar productos por categoría
    Route::get('/public/{slug}', [FrontController::class, 'categoria']);

    // Autenticación
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/login', [AuthController::class, 'login']);

    // Productos públicos
    Route::get('/productos', [ProductoApiController::class, 'index']);
    Route::get('/productos/{id}', [ProductoApiController::class, 'show']);

    ////////// RUTAS PRIVADAS //////////

    Route::middleware('auth:sanctum')->group(function () {

        // Logout
        Route::post('/auth/logout', [AuthController::class, 'logout']);

        //crear carpeta cliente
        // Recursos del cliente
        //Route::apiResource('/client/producto', ProductoClient::class);

        // Recursos del administrador
        Route::apiResource('/admin/user', UserController::class);
        Route::apiResource('/admin/categoria', CategoriaController::class);
        Route::apiResource('/client/producto', ProductoApiController::class);


        // Carrito
        Route::post('/carrito/{id}', [CarritoApiController::class, 'agregar']);

        Route::get('/carrito', [CarritoApiController::class, 'mostrar']);
        Route::delete('/carrito/{id}', [CarritoApiController::class, 'eliminar']);

        // Información del usuario autenticado
        Route::get('/user', function (Request $request) {
            return response()->json($request->user());
        });
    });
});
