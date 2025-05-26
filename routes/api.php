<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FrontController;
use App\Http\Controllers\Api\Admin\UserController;
use App\Http\Controllers\Api\Admin\ProductoController;
use App\Http\Controllers\Api\Admin\VentaController;
use App\Http\Controllers\Api\Admin\CategoriaController;
use App\Http\Controllers\Api\Client\ProductoController as ProductoClient;

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

    /// ventas ///
    Route::get('/ventas', [VentaController::class, 'index']);
    Route::get('/ventas/{id}', [VentaController::class, 'show']);

    ////////// RUTAS PRIVADAS //////////

    Route::middleware('auth:sanctum')->group(function () {

        // Logout
        Route::post('/auth/logout', [AuthController::class, 'logout']);

        //crear carpeta cliente
        // Recursos del cliente CLIENT
        Route::apiResource('/client/producto', ProductoClient::class);
        //Route::apiResource('/client/ventas', VentaController::class);

        // Recursos del administrador ADMIN
        Route::apiResource('/admin/user', UserController::class);
        Route::apiResource('/admin/categoria', CategoriaController::class);
        Route::apiResource('/admin/producto', ProductoController::class);

        /// ventas ///
        Route::apiResource('/admin/ventas', VentaController::class);

        // usuario autenticado
        Route::get('/user', function (Request $request) {
            return response()->json($request->user());
        });
    });
});
