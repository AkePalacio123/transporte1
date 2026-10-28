<?php

namespace App\Http\Controllers;

use App\Models\Operador;
use Illuminate\Http\Request;

class OperadorController extends Controller
{
    /**
     * Mostrar listado.
     */
    public function index(Request $request)
    {
        $buscar = $request->buscar;

        $operadores = Operador::when($buscar, function ($query) use ($buscar) {

                $query->where('nombre', 'like', "%{$buscar}%")
                    ->orWhere('telefono', 'like', "%{$buscar}%")
                    ->orWhere('licencia', 'like', "%{$buscar}%");

            })
            ->orderBy('nombre')
            ->paginate(10)
            ->withQueryString();

        return view('operadores.index', compact('operadores', 'buscar'));
    }

    /**
     * Mostrar formulario.
     */
    public function create()
    {
        return view('operadores.create');
    }

    /**
     * Guardar.
     */
    public function store(Request $request)
    {
        $request->validate([

            'nombre' => 'required|max:100',

            'telefono' => 'required|max:20',

            'licencia' => 'required|max:50|unique:operadores,licencia',

            'vencimiento_licencia' => 'required|date',

            'estatus' => 'required'

        ]);

        Operador::create($request->all());

        return redirect()
            ->route('operadores.index')
            ->with('success', 'Operador registrado correctamente.');
    }

    /**
     * Mostrar detalle.
     */
    public function show(Operador $operador)
    {
        return view('operadores.show', compact('operador'));
    }

    /**
     * Mostrar edición.
     */
    public function edit(Operador $operador)
    {
        return view('operadores.edit', compact('operador'));
    }

    /**
     * Actualizar.
     */
    public function update(Request $request, Operador $operador)
    {
        $request->validate([

            'nombre' => 'required|max:100',

            'telefono' => 'required|max:20',

            'licencia' => 'required|max:50|unique:operadores,licencia,' . $operador->id,

            'vencimiento_licencia' => 'required|date',

            'estatus' => 'required'

        ]);

        $operador->update($request->all());

        return redirect()
            ->route('operadores.index')
            ->with('success', 'Operador actualizado correctamente.');
    }

    /**
     * Eliminar.
     */
    public function destroy(Operador $operador)
    {
        $operador->delete();

        return redirect()
            ->route('operadores.index')
            ->with('success', 'Operador eliminado correctamente.');
    }
}