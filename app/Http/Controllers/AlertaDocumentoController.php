<?php

namespace App\Http\Controllers;

use App\Models\DocumentoTracto;
use App\Models\DocumentoEquipo;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AlertaDocumentoController extends Controller
{
    /**
     * Pantalla con el detalle de documentos vencidos o por vencer,
     * de tractos y equipos.
     */
    public function index()
    {
        $hoy    = Carbon::today();
        $margen = 30; // días de anticipación

        // --- Documentos de TRACTOS con fecha de vencimiento ---
        $docsTracto = DocumentoTracto::with('tracto')
            ->whereNotNull('fecha_vencimiento')
            ->get()
            ->map(function ($d) {
                return (object) [
                    'tipo_unidad' => 'Tracto',
                    'unidad'      => $d->tracto->serie ?? ('Tracto #' . $d->tracto_id),
                    'unidad_id'   => $d->tracto_id,
                    'ruta_show'   => 'tractos.show',
                    'categoria'   => $d->categoria,
                    'archivo'     => $d->nombre_archivo,
                    'ruta'        => $d->ruta,
                    'vence'       => $d->fecha_vencimiento,
                    'vencido'     => $d->esta_vencido,
                    'por_vencer'  => $d->esta_por_vencer,
                ];
            });

        // --- Documentos de EQUIPOS con fecha de vencimiento ---
        $docsEquipo = DocumentoEquipo::with('equipo')
            ->whereNotNull('fecha_vencimiento')
            ->get()
            ->map(function ($d) {
                return (object) [
                    'tipo_unidad' => 'Equipo',
                    'unidad'      => $d->equipo->serie ?? ('Equipo #' . $d->equipo_id),
                    'unidad_id'   => $d->equipo_id,
                    'ruta_show'   => 'equipos.show',
                    'categoria'   => $d->categoria,
                    'archivo'     => $d->nombre_archivo,
                    'ruta'        => $d->ruta,
                    'vence'       => $d->fecha_vencimiento,
                    'vencido'     => $d->esta_vencido,
                    'por_vencer'  => $d->esta_por_vencer,
                ];
            });

        // Unir ambos y quedarnos solo con los que están vencidos o por vencer
        $todos = $docsTracto->concat($docsEquipo)
            ->filter(fn ($d) => $d->vencido || $d->por_vencer)
            ->sortBy('vence')
            ->values();

        // Separar en dos grupos para la vista
        $vencidos   = $todos->filter(fn ($d) => $d->vencido)->values();
        $porVencer  = $todos->filter(fn ($d) => $d->por_vencer)->values();

        return view('alertas_documentos.index', [
            'vencidos'  => $vencidos,
            'porVencer' => $porVencer,
        ]);
    }
}