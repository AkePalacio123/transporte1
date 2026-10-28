<?php

namespace App\Http\Controllers;

use App\Models\DocumentoEquipo;
use App\Models\Equipo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentoEquipoController extends Controller
{
    public function store(Request $request, Equipo $equipo)
    {
        $request->validate([
            'categoria'         => ['required', 'in:' . implode(',', DocumentoEquipo::CATEGORIAS)],
            'archivo'           => ['required', 'file', 'mimes:pdf,jpg,jpeg,png,webp', 'max:10240'],
            'fecha_vencimiento' => ['nullable', 'date'],
            'observaciones'     => ['nullable', 'string', 'max:500'],
        ], [
            'archivo.mimes' => 'El documento debe ser PDF o imagen (JPG, PNG).',
            'archivo.max'   => 'El archivo no debe pesar más de 10 MB.',
            'categoria.in'  => 'Selecciona una categoría válida.',
        ]);

        $archivo = $request->file('archivo');
        $ruta = $archivo->store('documentos/equipos/' . $equipo->id, 'public');

        DocumentoEquipo::create([
            'equipo_id'         => $equipo->id,
            'categoria'         => $request->categoria,
            'nombre_archivo'    => $archivo->getClientOriginalName(),
            'ruta'              => $ruta,
            'fecha_vencimiento' => $request->fecha_vencimiento,
            'observaciones'     => $request->observaciones,
        ]);

        return back()->with('success', 'Documento subido correctamente.');
    }

    public function destroy(DocumentoEquipo $documento)
    {
        if ($documento->ruta) {
            Storage::disk('public')->delete($documento->ruta);
        }
        $documento->delete();

        return back()->with('success', 'Documento eliminado correctamente.');
    }
}