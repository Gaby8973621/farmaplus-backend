<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * No redirigir al login en APIs
     */
    protected function redirectTo($request)
    {
        // Devuelve null para que no intente redirigir a la ruta 'login'
        if (! $request->expectsJson()) {
            return null;
        }
    }

    /**
     * Respuesta JSON personalizada para usuarios no autenticados
     */
    protected function unauthenticated($request, array $guards)
    {
        abort(response()->json([
            'message' => 'No autenticado. Por favor inicia sesión primero.'
        ], 401));
    }
}
