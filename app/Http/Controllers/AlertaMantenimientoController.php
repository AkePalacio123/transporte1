<?php

namespace App\Http\Controllers;

use App\Models\Tracto;

class AlertaMantenimientoController extends Controller
{
    /**
     * Pantalla con los tractos según su avance de kilómetros
     * hacia el mantenimiento.
     */
    public function index()
    {
        // Traemos todos los tractos activos con sus relaciones necesarias
        $tractos = Tracto::with(['viajes', 'mantenimientos'])
            ->where('estatus', '!=', 'Baja')
            ->orderBy('serie')
            ->get();

        // Los clasificamos por su estado de kilómetros
        $criticos = $tractos->filter(fn ($t) => $t->mantenimiento_vencido)
            ->sortByDesc('km_acumulados')->values();

        $enAlarma = $tractos->filter(fn ($t) => $t->requiere_mantenimiento && ! $t->mantenimiento_vencido)
            ->sortByDesc('km_acumulados')->values();

        $normales = $tractos->filter(fn ($t) => ! $t->requiere_mantenimiento)
            ->sortByDesc('km_acumulados')->values();

        return view('alertas_mantenimiento.index', [
            'criticos'  => $criticos,
            'enAlarma'  => $enAlarma,
            'normales'  => $normales,
            'kmAlarma'  => Tracto::KM_ALARMA,
            'kmLimite'  => Tracto::KM_MANTENIMIENTO,
        ]);
    }
}