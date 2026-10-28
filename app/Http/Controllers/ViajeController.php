<?php

namespace App\Http\Controllers;

use App\Http\Requests\Viajes\StoreViajeRequest;
use App\Http\Requests\Viajes\UpdateViajeRequest;
use App\Models\Empresa;
use App\Models\Equipo;
use App\Models\Operador;
use App\Models\Tracto;
use App\Models\Viaje;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class ViajeController extends Controller
{
    /**
     * Listado de viajes con buscador y filtros.
     */
    public function index(Request $request)
    {
        $viajes = Viaje::with(['empresa', 'operador', 'tracto', 'equipo'])
            ->buscar($request->buscar)
            ->filtrarPor('empresa_id', $request->empresa_id)
            ->filtrarPor('operador_id', $request->operador_id)
            ->filtrarPor('estatus', $request->estatus)
            ->filtrarPor('estatus_pago', $request->estatus_pago)
            ->filtrarPor('tipo_viaje', $request->tipo_viaje)
            ->conAlerta($request->alerta)
            ->entreFechas($request->desde, $request->hasta)
            ->orderByDesc('fecha_viaje')
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();
            

        // Totales del resultado filtrado (util para reportes)
        $totales = [
            'cantidad' => $viajes->total(),
        ];

        return view('viajes.index', [
            'viajes'     => $viajes,
            'totales'    => $totales,
            'empresas'   => Empresa::orderBy('nombre')->get(),
            'operadores' => Operador::orderBy('id')->get(),
            'filtros' => $request->only([
                'buscar', 'empresa_id', 'operador_id', 'estatus',
                'estatus_pago', 'tipo_viaje', 'alerta', 'desde', 'hasta',
            ]),
        ]);
    }

    /**
     * Formulario de nuevo viaje.
     */
    public function create()
    {
        return view('viajes.create', $this->datosFormulario());
    }

    /**
     * Guardar un viaje nuevo.
     */
    public function store(StoreViajeRequest $request)
    {
        $datos = $request->validated();

        // Guardar el PDF de la bitacora, si viene.
        if ($request->hasFile('archivo_bitacora')) {
            $datos['archivo_bitacora'] = $request
                ->file('archivo_bitacora')
                ->store('bitacoras', 'public');
        }

        $viaje = Viaje::create($datos);

        return redirect()
            ->route('viajes.show', $viaje)
            ->with('success', 'Viaje registrado correctamente.');
    }

    /**
     * Detalle de un viaje.
     */
    public function show(Viaje $viaje)
    {
        $viaje->load(['empresa', 'operador', 'tracto', 'equipo', 'casetas', 'cargasCombustible']);

        return view('viajes.show', compact('viaje'));
    }

    /**
     * Formulario de edicion.
     */
    public function edit(Viaje $viaje)
    {
        return view('viajes.edit', array_merge(
            $this->datosFormulario(),
            ['viaje' => $viaje]
        ));
    }

    /**
     * Actualizar un viaje.
     */
    public function update(UpdateViajeRequest $request, Viaje $viaje)
    {
        $datos = $request->validated();

        if ($request->hasFile('archivo_bitacora')) {

            // Borrar el PDF anterior para no dejar basura en el disco.
            if ($viaje->archivo_bitacora) {
                Storage::disk('public')->delete($viaje->archivo_bitacora);
            }

            $datos['archivo_bitacora'] = $request
                ->file('archivo_bitacora')
                ->store('bitacoras', 'public');
        }

        $viaje->update($datos);

        return redirect()
            ->route('viajes.show', $viaje)
            ->with('success', 'Viaje actualizado correctamente.');
    }

    /**
     * Eliminar un viaje y su PDF asociado.
     */
    public function destroy(Viaje $viaje)
    {
        if ($viaje->archivo_bitacora) {
            Storage::disk('public')->delete($viaje->archivo_bitacora);
        }

        $viaje->delete();

        return redirect()
            ->route('viajes.index')
            ->with('success', 'Viaje eliminado correctamente.');
    }

     /**
     * Devuelve en JSON la asignación activa de un operador.
     * La usa el formulario de viajes para autocompletar tracto y equipo.
     */
    public function asignacionDeOperador(\App\Models\Operador $operador)
    {
        $asignacion = \App\Models\Asignacion::where('operador_id', $operador->id)
            ->where('activa', true)
            ->latest('fecha_inicio')
            ->first();
 
        if (! $asignacion) {
            return response()->json(['tiene' => false]);
        }
 
        return response()->json([
            'tiene'     => true,
            'tracto_id' => $asignacion->tracto_id,
            'equipo_id' => $asignacion->equipo_id,
        ]);
    }

    /**
     * Catalogos que necesitan los formularios de crear y editar.
     */
    private function datosFormulario(): array
    {
        return [
            'empresas'   => Empresa::orderBy('nombre')->get(),
            'operadores' => Operador::orderBy('id')->get(),
            'tractos'    => Tracto::orderBy('id')->get(),
            'equipos'    => Equipo::orderBy('id')->get(),
        ];
    }
}