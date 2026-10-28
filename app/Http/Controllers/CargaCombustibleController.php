<?php

namespace App\Http\Controllers;

use App\Http\Requests\Combustible\StoreCargaCombustibleRequest;
use App\Http\Requests\Combustible\UpdateCargaCombustibleRequest;
use App\Models\CargaCombustible;
use App\Models\Viaje;

class CargaCombustibleController extends Controller
{
    /**
     * Registrar una carga de combustible en un viaje.
     */
    public function store(StoreCargaCombustibleRequest $request, Viaje $viaje)
    {
        $viaje->cargasCombustible()->create($request->validated());

        return redirect()
            ->route('viajes.show', $viaje)
            ->with('success', 'Carga de combustible registrada correctamente.');
    }

    /**
     * Formulario de edicion.
     */
    public function edit(CargaCombustible $combustible)
    {
        $combustible->load('viaje');

        return view('combustible.edit', compact('combustible'));
    }

    /**
     * Actualizar una carga.
     */
    public function update(UpdateCargaCombustibleRequest $request, CargaCombustible $combustible)
    {
        $combustible->update($request->validated());

        return redirect()
            ->route('viajes.show', $combustible->viaje_id)
            ->with('success', 'Carga de combustible actualizada correctamente.');
    }

    /**
     * Eliminar una carga.
     */
    public function destroy(CargaCombustible $combustible)
    {
        $viajeId = $combustible->viaje_id;

        $combustible->delete();

        return redirect()
            ->route('viajes.show', $viajeId)
            ->with('success', 'Carga de combustible eliminada correctamente.');
    }
}