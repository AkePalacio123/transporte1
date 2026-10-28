<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Permite el paso solo si el usuario tiene uno de los roles indicados.
     * Uso en rutas:  ->middleware('rol:Administrador')
     *                ->middleware('rol:Administrador,Capturista')
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user || ! $user->activo) {
            abort(403, 'Tu cuenta no está activa.');
        }

        if (! empty($roles) && ! in_array($user->rol, $roles, true)) {
            abort(403, 'No tienes permisos para acceder a esta sección.');
        }

        return $next($request);
    }
}