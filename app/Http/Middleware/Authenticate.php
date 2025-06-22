<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Obtiene la ruta a la que se debe redirigir al usuario cuando no está autenticado.
     */
    protected function redirectTo(Request $request): ?string
    {
        // Este método se llama cuando un usuario no autenticado intenta acceder a una ruta protegida.
        // Devuelve la ruta a la que redirigir.
        return $request->expectsJson() ? null : route('login');
    }
}