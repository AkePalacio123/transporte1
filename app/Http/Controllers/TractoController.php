<?php

namespace App\Http\Controllers;

use App\Http\Requests\Tractos\StoreTractoRequest;
use App\Http\Requests\Tractos\UpdateTractoRequest;
use App\Models\Tracto;
use Illuminate\Http\Request;

class TractoController extends Controller
{
    public function index(Request $request)
    {
        $buscar  = $request->buscar;
        $estatus = $request->estatus;

        $tractos = Tracto::buscar($buscar)
            ->when($estatus, fn ($q) => $q->where('estatus', $estatus))
            ->withCount('viajes')
            ->orderBy('serie')
            ->paginate(10)
            ->withQueryString();

        return view('tractos.index', compact('tractos', 'buscar', 'estatus'));
    }

    public function create()
    {
        return view('tractos.create');
    }

    public function store(StoreTractoRequest $request)
    {
        Tracto::create($request->validated());

        return redirect()
            ->route('tractos.index')
            ->with('success', 'Tracto registrado correctamente.');
    }

    public function show(Tracto $tracto)
    {
        $tracto->loadCount('viajes');

        $ultimosViajes = $tracto->viajes()
            ->with(['empresa', 'operador'])
            ->orderByDesc('fecha_viaje')
            ->limit(10)
            ->get();

        return view('tractos.show', compact('tracto', 'ultimosViajes'));
    }

    public function edit(Tracto $tracto)
    {
        return view('tractos.edit', compact('tracto'));
    }

    public function update(UpdateTractoRequest $request, Tracto $tracto)
    {
        $tracto->update($request->validated());

        return redirect()
            ->route('tractos.index')
            ->with('success', 'Tracto actualizado correctamente.');
    }

    public function destroy(Tracto $tracto)
    {
        if ($tracto->viajes()->exists()) {
            return redirect()
                ->route('tractos.index')
                ->with('error', 'No se puede eliminar este tracto porque tiene viajes registrados.');
        }

        $tracto->delete();

        return redirect()
            ->route('tractos.index')
            ->with('success', 'Tracto eliminado correctamente.');
    }
}