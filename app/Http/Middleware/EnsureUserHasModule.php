<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasModule
{
    /**
     * Verifica que el usuario tenga acceso al módulo indicado.
     * Se usa en las rutas así:  ->middleware('modulo:viajes')
     */
    public function handle(Request $request, Closure $next, string $modulo): Response
    {
        $user = $request->user();

        if (! $user || ! $user->puedeVerModulo($modulo)) {
            abort(403, 'No tienes acceso a este módulo.');
        }

        return $next($request);
    }
}