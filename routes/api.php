<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FrontController;
use App\Http\Controllers\Api\Admin\UserController;
use App\Http\Controllers\Api\Admin\CategoriaController;
use App\Http\Controllers\Api\ProductoApiController;
use App\Http\Controllers\Api\CarritoApiController;

//rutas con el prefijo de v1
Route::prefix('v1')->group(function () {
    //////////RUTAS PUBLICAS////////////////
    //para mostrar todos los roductos de una categoria
    //las rutas publicas no le vamos a poner autentificacion
    Route::get('/public/{slug}',[FrontController::class,'categoria']);

    //prefijo de login y registro va a ser auth
    Route::post('/auth/register',[AuthController::class,'register']);
    Route::post('/auth/login',[AuthController::class,'login']);

    // Rutas públicas
    Route::get('/productos', [ProductoApiController::class, 'index']);
    Route::get('/productos/{id}', [ProductoApiController::class, 'show']);

    ////////////RUTAS PRIVADAS////////////
    // a estas rutas si le vamos a poner autentificacion
    //vamos a usar un token
    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::post('/auth/logout', [AuthController::class, 'logout']);

        //rutas para el cliente
        Route::apiResource('/client/producto', ProductoController::class);

        //rutas para el admin
        Route::apiResource('/admin/user', UserController::class);
        Route::apiResource('/admin/categoria', CategoriaController::class);
    });


});

// Rutas protegidas con Sanctum (requiere autenticación)

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
    Route::post('/carrito/{id}', [CarritoApiController::class, 'agregar']);
    Route::get('/carrito', [CarritoApiController::class, 'mostrar']);
    Route::delete('/carrito/{id}', [CarritoApiController::class, 'eliminar']);

});
