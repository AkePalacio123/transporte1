<?php

namespace App\Http\Controllers;

use App\Models\Operador;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CalificacionOperadorController extends Controller
{
    /**
     * Pantalla de calificación de operadores por mes.
     */
    public function index(Request $request)
    {
        // Mes y año seleccionados (por defecto, el mes actual)
        $hoy  = Carbon::today();
        $anio = (int) $request->input('anio', $hoy->year);
        $mes  = (int) $request->input('mes', $hoy->month);

        // Calcular el rendimiento de cada operador en ese mes
        $operadores = Operador::orderBy('nombre')->get();

        $calificaciones = $operadores->map(function ($op) use ($anio, $mes) {
            return $op->rendimientoDelMes($anio, $mes);
        })
        // Mostrar primero los que tienen viajes ese mes
        ->sortByDesc('total_viajes')
        ->values();

        // Para el selector de meses
        $nombresMes = [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre',
        ];

        // Años disponibles (de este año hacia atrás 4 años)
        $anios = range($hoy->year, $hoy->year - 4);

        return view('calificacion_operadores.index', [
            'calificaciones' => $calificaciones,
            'anio'           => $anio,
            'mes'            => $mes,
            'nombresMes'     => $nombresMes,
            'anios'          => $anios,
            'minimo'         => Operador::RENDIMIENTO_MINIMO,
        ]);
    }
}