<?php

namespace App\Http\Controllers;

use App\Http\Requests\Mantenimientos\StorePiezaRequest;
use App\Models\Mantenimiento;
use App\Models\PiezaMantenimiento;

class PiezaMantenimientoController extends Controller
{
    /**
     * Agregar una pieza al mantenimiento.
     */
    public function store(StorePiezaRequest $request, Mantenimiento $mantenimiento)
    {
        $mantenimiento->piezas()->create($request->validated());

        return redirect()
            ->route('mantenimientos.show', $mantenimiento)
            ->with('success', 'Pieza agregada correctamente.');
    }

    /**
     * Eliminar una pieza.
     */
    public function destroy(PiezaMantenimiento $pieza)
    {
        $mantenimientoId = $pieza->mantenimiento_id;

        $pieza->delete();

        return redirect()
            ->route('mantenimientos.show', $mantenimientoId)
            ->with('success', 'Pieza eliminada correctamente.');
    }
}