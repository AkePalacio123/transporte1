<?php

namespace App\Http\Controllers;

use App\Models\DocumentoTracto;
use App\Models\Tracto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentoTractoController extends Controller
{
    public function store(Request $request, Tracto $tracto)
    {
        $request->validate([
            'categoria'         => ['required', 'in:' . implode(',', DocumentoTracto::CATEGORIAS)],
            'archivo'           => ['required', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:10240'],
            'fecha_vencimiento' => ['nullable', 'date'],
            'observaciones'     => ['nullable', 'string', 'max:500'],
        ], [
            'archivo.mimes' => 'El documento debe ser PDF o imagen (JPG, PNG).',
            'archivo.max'   => 'El archivo no debe pesar más de 10 MB.',
            'categoria.in'  => 'Selecciona una categoría válida.',
        ]);

        $archivo = $request->file('archivo');
        $ruta = $archivo->store('documentos/tractos/' . $tracto->id, 'public');

        DocumentoTracto::create([
            'tracto_id'         => $tracto->id,
            'categoria'         => $request->categoria,
            'nombre_archivo'    => $archivo->getClientOriginalName(),
            'ruta'              => $ruta,
            'fecha_vencimiento' => $request->fecha_vencimiento,
            'observaciones'     => $request->observaciones,
        ]);

        return back()->with('success', 'Documento subido correctamente.');
    }

    public function destroy(DocumentoTracto $documento)
    {
        if ($documento->ruta) {
            Storage::disk('public')->delete($documento->ruta);
        }
        $documento->delete();

        return back()->with('success', 'Documento eliminado correctamente.');
    }
}