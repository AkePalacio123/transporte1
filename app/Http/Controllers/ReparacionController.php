<?php

namespace App\Http\Controllers;

use App\Models\Reparacion;
use App\Models\Viaje;
use Illuminate\Http\Request;

class ReparacionController extends Controller
{
    /**
     * Registrar una reparación en un viaje.
     */
    public function store(Request $request, Viaje $viaje)
    {
        $datos = $request->validate([
            'descripcion'   => ['required', 'string', 'max:255'],
            'costo'         => ['required', 'numeric', 'min:0', 'max:9999999'],
            'fecha'         => ['nullable', 'date'],
            'observaciones' => ['nullable', 'string', 'max:500'],
        ], [
            'descripcion.required' => 'Describe la reparación.',
            'costo.required'       => 'Indica el costo de la reparación.',
        ]);

        $viaje->reparaciones()->create($datos);

        return redirect()
            ->route('viajes.show', $viaje)
            ->with('success', 'Reparación registrada correctamente.');
    }

    /**
     * Eliminar una reparación.
     */
    public function destroy(Reparacion $reparacion)
    {
        $viajeId = $reparacion->viaje_id;
        $reparacion->delete();

        return redirect()
            ->route('viajes.show', $viajeId)
            ->with('success', 'Reparación eliminada correctamente.');
    }
}