<?php

namespace App\Http\Controllers;

use App\Http\Requests\Equipos\StoreEquipoRequest;
use App\Http\Requests\Equipos\UpdateEquipoRequest;
use App\Models\Equipo;
use Illuminate\Http\Request;

class EquipoController extends Controller
{
    public function index(Request $request)
    {
        $buscar  = $request->buscar;
        $estatus = $request->estatus;

        $equipos = Equipo::buscar($buscar)
            ->when($estatus, fn ($q) => $q->where('estatus', $estatus))
            ->withCount('viajes')
            ->orderBy('serie')
            ->paginate(10)
            ->withQueryString();

        return view('equipos.index', compact('equipos', 'buscar', 'estatus'));
    }

    public function create()
    {
        return view('equipos.create');
    }

    public function store(StoreEquipoRequest $request)
    {
        Equipo::create($request->validated());

        return redirect()
            ->route('equipos.index')
            ->with('success', 'Equipo registrado correctamente.');
    }

    public function show(Equipo $equipo)
    {
        $equipo->loadCount('viajes');

        $ultimosViajes = $equipo->viajes()
            ->with(['empresa', 'operador'])
            ->orderByDesc('fecha_viaje')
            ->limit(10)
            ->get();

        return view('equipos.show', compact('equipo', 'ultimosViajes'));
    }

    public function edit(Equipo $equipo)
    {
        return view('equipos.edit', compact('equipo'));
    }

    public function update(UpdateEquipoRequest $request, Equipo $equipo)
    {
        $equipo->update($request->validated());

        return redirect()
            ->route('equipos.index')
            ->with('success', 'Equipo actualizado correctamente.');
    }

    public function destroy(Equipo $equipo)
    {
        if ($equipo->viajes()->exists()) {
            return redirect()
                ->route('equipos.index')
                ->with('error', 'No se puede eliminar este equipo porque tiene viajes registrados.');
        }

        $equipo->delete();

        return redirect()
            ->route('equipos.index')
            ->with('success', 'Equipo eliminado correctamente.');
    }
}