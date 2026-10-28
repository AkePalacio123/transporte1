<?php

namespace App\Http\Controllers;

use App\Http\Requests\Asignaciones\StoreAsignacionRequest;
use App\Http\Requests\Asignaciones\UpdateAsignacionRequest;
use App\Models\Asignacion;
use App\Models\Equipo;
use App\Models\Operador;
use App\Models\Tracto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AsignacionController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->buscar;
        $activa = $request->activa;

        $asignaciones = Asignacion::with(['operador', 'tracto', 'equipo'])
            ->buscar($buscar)
            ->when($activa !== null && $activa !== '', fn ($q) => $q->where('activa', (bool) $activa))
            ->orderByDesc('activa')
            ->orderByDesc('fecha_inicio')
            ->paginate(15)
            ->withQueryString();

        return view('asignaciones.index', compact('asignaciones', 'buscar', 'activa'));
    }

    public function create()
    {
        return view('asignaciones.create', $this->catalogos());
    }

    public function store(StoreAsignacionRequest $request)
    {
        $datos = $request->validated();

        DB::transaction(function () use ($datos) {
            // Al crear una asignación activa, cerramos las anteriores
            // del mismo operador y del mismo tracto.
            if (! empty($datos['activa'])) {
                $this->cerrarAnteriores($datos['operador_id'], $datos['tracto_id'], $datos['fecha_inicio']);
            }

            Asignacion::create($datos);
        });

        return redirect()
            ->route('asignaciones.index')
            ->with('success', 'Asignación registrada correctamente.');
    }

    public function show(Asignacion $asignacion)
    {
        $asignacion->load(['operador', 'tracto', 'equipo']);

        return view('asignaciones.show', compact('asignacion'));
    }

    public function edit(Asignacion $asignacion)
    {
        return view('asignaciones.edit', array_merge(
            $this->catalogos(),
            ['asignacion' => $asignacion]
        ));
    }

    public function update(UpdateAsignacionRequest $request, Asignacion $asignacion)
    {
        $datos = $request->validated();

        DB::transaction(function () use ($datos, $asignacion) {
            if (! empty($datos['activa'])) {
                $this->cerrarAnteriores(
                    $datos['operador_id'],
                    $datos['tracto_id'],
                    $datos['fecha_inicio'],
                    $asignacion->id
                );
            }

            $asignacion->update($datos);
        });

        return redirect()
            ->route('asignaciones.index')
            ->with('success', 'Asignación actualizada correctamente.');
    }

    public function destroy(Asignacion $asignacion)
    {
        $asignacion->delete();

        return redirect()
            ->route('asignaciones.index')
            ->with('success', 'Asignación eliminada correctamente.');
    }

    /**
     * Cierra (desactiva) las asignaciones activas previas del operador
     * y del tracto, para que solo exista una vigente a la vez.
     */
    private function cerrarAnteriores($operadorId, $tractoId, $fechaInicio, $exceptoId = null): void
    {
        Asignacion::where('activa', true)
            ->where(function ($q) use ($operadorId, $tractoId) {
                $q->where('operador_id', $operadorId)
                  ->orWhere('tracto_id', $tractoId);
            })
            ->when($exceptoId, fn ($q) => $q->where('id', '!=', $exceptoId))
            ->update([
                'activa'    => false,
                'fecha_fin' => $fechaInicio,
            ]);
    }

    private function catalogos(): array
    {
        return [
            'operadores' => Operador::orderBy('nombre')->get(),
            'tractos'    => Tracto::orderBy('serie')->get(),
            'equipos'    => Equipo::orderBy('serie')->get(),
        ];
    }
}