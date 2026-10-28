<?php

namespace App\Http\Controllers;

use App\Models\DocumentoOperador;
use App\Models\Operador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentoOperadorController extends Controller
{
    /**
     * Guardar un documento nuevo para un operador.
     */
    public function store(Request $request, Operador $operador)
    {
        $request->validate([
            'categoria'         => ['required', 'in:' . implode(',', DocumentoOperador::CATEGORIAS)],
            'archivo'           => ['required', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:10240'],
            'fecha_vencimiento' => ['nullable', 'date'],
            'observaciones'     => ['nullable', 'string', 'max:500'],
        ], [
            'archivo.mimes' => 'El documento debe ser PDF o imagen (JPG, PNG).',
            'archivo.max'   => 'El archivo no debe pesar más de 10 MB.',
            'categoria.in'  => 'Selecciona una categoría válida.',
        ]);

        // Guardar el archivo físico
        $archivo = $request->file('archivo');
        $ruta = $archivo->store('documentos/operadores/' . $operador->id, 'public');

        DocumentoOperador::create([
            'operador_id'       => $operador->id,
            'categoria'         => $request->categoria,
            'nombre_archivo'    => $archivo->getClientOriginalName(),
            'ruta'              => $ruta,
            'fecha_vencimiento' => $request->fecha_vencimiento,
            'observaciones'     => $request->observaciones,
        ]);

        return back()->with('success', 'Documento subido correctamente.');
    }

    /**
     * Eliminar un documento.
     */
    public function destroy(DocumentoOperador $documento)
    {
        // Borrar el archivo físico del disco
        if ($documento->ruta) {
            Storage::disk('public')->delete($documento->ruta);
        }

        $documento->delete();

        return back()->with('success', 'Documento eliminado correctamente.');
    }
}