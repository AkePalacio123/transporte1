<?php

namespace App\Http\Controllers;

use App\Http\Requests\Mantenimientos\StoreMantenimientoRequest;
use App\Http\Requests\Mantenimientos\UpdateMantenimientoRequest;
use App\Models\Equipo;
use App\Models\Mantenimiento;
use App\Models\Tracto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MantenimientoController extends Controller
{
    /**
     * Listado con buscador y filtros.
     */
    public function index(Request $request)
    {
        $mantenimientos = Mantenimiento::with(['tracto', 'equipo', 'piezas'])
            ->buscar($request->buscar)
            ->filtrarPor('tipo', $request->tipo)
            ->filtrarPor('estatus', $request->estatus)
            ->deUnidad($request->unidad)
            ->entreFechas($request->desde, $request->hasta)
            ->orderByDesc('fecha_entrada')
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view('mantenimientos.index', [
            'mantenimientos' => $mantenimientos,
            'unidades'       => $this->listaUnidades(),
            'filtros'        => $request->only([
                'buscar', 'tipo', 'estatus', 'unidad', 'desde', 'hasta',
            ]),
        ]);
    }

    public function create()
    {
        return view('mantenimientos.create', [
            'unidades' => $this->listaUnidades(),
        ]);
    }

    public function store(StoreMantenimientoRequest $request)
    {
        $datos = $this->prepararDatos($request);

        if ($request->hasFile('factura_pdf')) {
            $datos['factura_pdf'] = $request->file('factura_pdf')
                ->store('facturas_mantenimiento', 'public');
        }

        $mantenimiento = Mantenimiento::create($datos);

        return redirect()
            ->route('mantenimientos.show', $mantenimiento)
            ->with('success', 'Mantenimiento registrado correctamente.');
    }

    public function show(Mantenimiento $mantenimiento)
    {
        $mantenimiento->load(['tracto', 'equipo', 'piezas']);

        return view('mantenimientos.show', compact('mantenimiento'));
    }

    public function edit(Mantenimiento $mantenimiento)
    {
        return view('mantenimientos.edit', [
            'mantenimiento' => $mantenimiento,
            'unidades'      => $this->listaUnidades(),
        ]);
    }

    public function update(UpdateMantenimientoRequest $request, Mantenimiento $mantenimiento)
    {
        $datos = $this->prepararDatos($request);

        if ($request->hasFile('factura_pdf')) {
            if ($mantenimiento->factura_pdf) {
                Storage::disk('public')->delete($mantenimiento->factura_pdf);
            }
            $datos['factura_pdf'] = $request->file('factura_pdf')
                ->store('facturas_mantenimiento', 'public');
        }

        $mantenimiento->update($datos);

        return redirect()
            ->route('mantenimientos.show', $mantenimiento)
            ->with('success', 'Mantenimiento actualizado correctamente.');
    }

    public function destroy(Mantenimiento $mantenimiento)
    {
        if ($mantenimiento->factura_pdf) {
            Storage::disk('public')->delete($mantenimiento->factura_pdf);
        }

        $mantenimiento->delete();

        return redirect()
            ->route('mantenimientos.index')
            ->with('success', 'Mantenimiento eliminado correctamente.');
    }

    /* ------------------------------------------------------------------
     | AUXILIARES
     | -----------------------------------------------------------------*/

    /**
     * Convierte el valor 'tracto-3' o 'equipo-5' en las columnas correctas.
     */
    private function prepararDatos($request): array
    {
        $datos = $request->validated();

        [$tipo, $id] = explode('-', $request->unidad, 2);

        $datos['tracto_id'] = $tipo === 'tracto' ? (int) $id : null;
        $datos['equipo_id'] = $tipo === 'equipo' ? (int) $id : null;

        unset($datos['unidad']);

        return $datos;
    }

    /**
     * Lista combinada de tractos y equipos para los selects.
     */
    private function listaUnidades(): array
    {
        $lista = [];

        foreach (Tracto::orderBy('serie')->get() as $tracto) {
            $lista[] = [
                'valor'  => 'tracto-' . $tracto->id,
                'texto'  => 'Tracto ' . $tracto->serie . ' - ' . $tracto->placa,
                'grupo'  => 'Tractos',
            ];
        }

        foreach (Equipo::orderBy('serie')->get() as $equipo) {
            $lista[] = [
                'valor'  => 'equipo-' . $equipo->id,
                'texto'  => 'Equipo ' . $equipo->serie . ' - ' . $equipo->placa,
                'grupo'  => 'Equipos',
            ];
        }

        return $lista;
    }
}