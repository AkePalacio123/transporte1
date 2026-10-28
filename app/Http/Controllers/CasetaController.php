<?php

namespace App\Http\Controllers;

use App\Http\Requests\Casetas\StoreCasetaRequest;
use App\Http\Requests\Casetas\UpdateCasetaRequest;
use App\Models\Caseta;
use App\Models\Empresa;
use App\Models\Operador;
use App\Models\Viaje;
use Illuminate\Http\Request;

class CasetaController extends Controller
{
    /**
     * Listado general de todas las casetas (módulo propio).
     * Solo consulta y exportación; el alta se sigue haciendo
     * desde la pantalla del viaje.
     */
    public function index(Request $request)
    {
        $casetas = Caseta::with(['viaje.empresa', 'viaje.operador'])
            ->buscar($request->buscar)
            ->deEmpresa($request->empresa_id)
            ->deOperador($request->operador_id)
            ->conPagoViaje($request->estatus_pago)
            ->entreFechas($request->desde, $request->hasta)
            ->orderByDesc('fecha')
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        // Total de lo filtrado (para mostrar la suma)
        $total = (clone $casetas)->getCollection()->sum('costo');

        // Suma real de TODO lo filtrado (no solo la página actual)
        $sumaTotal = Caseta::buscar($request->buscar)
            ->deEmpresa($request->empresa_id)
            ->deOperador($request->operador_id)
            ->conPagoViaje($request->estatus_pago)
            ->entreFechas($request->desde, $request->hasta)
            ->sum('costo');

        return view('casetas.index', [
            'casetas'    => $casetas,
            'sumaTotal'  => $sumaTotal,
            'empresas'   => Empresa::orderBy('nombre')->get(),
            'operadores' => Operador::orderBy('nombre')->get(),
            'filtros'    => $request->only([
                'buscar', 'empresa_id', 'operador_id', 'estatus_pago', 'desde', 'hasta',
            ]),
        ]);
    }

    /**
     * Agregar una caseta a un viaje.
     */
    public function store(StoreCasetaRequest $request, Viaje $viaje)
    {
        $viaje->casetas()->create($request->validated());

        return redirect()
            ->route('viajes.show', $viaje)
            ->with('success', 'Caseta agregada correctamente.');
    }

    /**
     * Formulario de edicion de una caseta.
     */
    public function edit(Caseta $caseta)
    {
        $caseta->load('viaje');
        return view('casetas.edit', compact('caseta'));
    }

    /**
     * Actualizar una caseta.
     */
    public function update(UpdateCasetaRequest $request, Caseta $caseta)
    {
        $caseta->update($request->validated());

        return redirect()
            ->route('viajes.show', $caseta->viaje_id)
            ->with('success', 'Caseta actualizada correctamente.');
    }

    /**
     * Eliminar una caseta.
     */
    public function destroy(Caseta $caseta)
    {
        $viajeId = $caseta->viaje_id;
        $caseta->delete();

        return redirect()
            ->route('viajes.show', $viajeId)
            ->with('success', 'Caseta eliminada correctamente.');
    }
}