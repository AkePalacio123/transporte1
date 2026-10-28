<?php

namespace App\Http\Controllers;

use App\Models\Guia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GuiaController extends Controller
{
    /**
     * Listado de guías con buscador y filtro por categoría.
     */
    public function index(Request $request)
    {
        $guias = Guia::buscar($request->buscar)
            ->filtrarCategoria($request->categoria)
            ->orderBy('categoria')
            ->orderBy('titulo')
            ->paginate(12)
            ->withQueryString();

        return view('guias.index', [
            'guias'   => $guias,
            'filtros' => $request->only(['buscar', 'categoria']),
        ]);
    }

    /**
     * Formulario para subir una guía nueva.
     */
    public function create()
    {
        return view('guias.create');
    }

    /**
     * Guardar una guía nueva.
     */
    public function store(Request $request)
    {
        $request->validate([
            'titulo'      => ['required', 'string', 'max:150'],
            'categoria'   => ['required', 'in:' . implode(',', Guia::CATEGORIAS)],
            'descripcion' => ['nullable', 'string', 'max:1000'],
            'archivo'     => ['required', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:10240'],
        ], [
            'archivo.mimes' => 'La guía debe ser un PDF o una imagen (JPG, PNG).',
            'archivo.max'   => 'El archivo no debe pesar más de 10 MB.',
            'categoria.in'  => 'Selecciona una categoría válida.',
        ]);

        $archivo = $request->file('archivo');
        $ruta = $archivo->store('guias', 'public');

        Guia::create([
            'titulo'         => $request->titulo,
            'categoria'      => $request->categoria,
            'descripcion'    => $request->descripcion,
            'nombre_archivo' => $archivo->getClientOriginalName(),
            'ruta'           => $ruta,
        ]);

        return redirect()
            ->route('guias.index')
            ->with('success', 'Guía subida correctamente.');
    }

    /**
     * Eliminar una guía.
     */
    public function destroy(Guia $guia)
    {
        if ($guia->ruta) {
            Storage::disk('public')->delete($guia->ruta);
        }

        $guia->delete();

        return redirect()
            ->route('guias.index')
            ->with('success', 'Guía eliminada correctamente.');
    }
}