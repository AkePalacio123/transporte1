<?php

namespace App\Http\Controllers;

use App\Models\CargaCombustible;
use App\Models\Caseta;
use App\Models\Empresa;
use App\Models\Equipo;
use App\Models\Operador;
use App\Models\Tracto;
use App\Models\Viaje;
use App\Models\DocumentoTracto;
use App\Models\DocumentoEquipo;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $hoy         = Carbon::today();
        $inicioMes   = $hoy->copy()->startOfMonth();
        $finMes      = $hoy->copy()->endOfMonth();

        /* ---------------------------------------------------------
         | TARJETAS DE INDICADORES (KPIs)
         |---------------------------------------------------------*/
        $kpis = [
            'viajes_total'      => Viaje::count(),
            'viajes_mes'        => Viaje::whereBetween('fecha_viaje', [$inicioMes, $finMes])->count(),
            'viajes_en_ruta'    => Viaje::where('estatus', 'En ruta')->count(),
            'viajes_pendientes' => Viaje::where('estatus_pago', 'Pendiente')
                                        ->where('estatus', '!=', 'Cancelado')
                                        ->count(),

            'ingresos_cobrados' => (float) Viaje::where('estatus_pago', 'Pagado')->sum('pago'),
            'por_cobrar'        => (float) Viaje::where('estatus_pago', 'Pendiente')
                                        ->where('estatus', '!=', 'Cancelado')
                                        ->sum('pago'),

            'gasto_casetas'     => (float) Caseta::sum('costo'),
            'gasto_combustible' => (float) CargaCombustible::sum('total'),
            'litros_total'      => (float) CargaCombustible::sum('litros'),

            'bitacoras_pendientes' => Viaje::where('estatus_bitacora', 'No entregada')->count(),

            'operadores'        => Operador::count(),
            'tractos'           => Tracto::count(),
            'equipos'           => Equipo::count(),
            'empresas'          => Empresa::count(),
        ];

        // Utilidad estimada: lo cobrado menos los gastos registrados.
        $kpis['utilidad_estimada'] = $kpis['ingresos_cobrados']
            - $kpis['gasto_casetas']
            - $kpis['gasto_combustible'];

        /* ---------------------------------------------------------
         | ALERTAS DE PAGO (vencidos y proximos a vencer)
         |---------------------------------------------------------*/
        $margen = Viaje::DIAS_AVISO_VENCIMIENTO;

        // Viajes con pago pendiente y fecha limite ya pasada (vencidos).
        $vencidos = Viaje::pagosPendientes()
            ->whereNotNull('fecha_limite_pago')
            ->whereDate('fecha_limite_pago', '<', $hoy)
            ->get();

        // Viajes con pago pendiente y limite dentro de los proximos 7 dias.
        $proximos = Viaje::pagosPendientes()
            ->whereNotNull('fecha_limite_pago')
            ->whereDate('fecha_limite_pago', '>=', $hoy)
            ->whereDate('fecha_limite_pago', '<=', $hoy->copy()->addDays($margen))
            ->get();

        $alertasPago = [
            'vencidos_cantidad'    => $vencidos->count(),
            'vencidos_monto'       => (float) $vencidos->sum('pago'),
            'proximos_cantidad'    => $proximos->count(),
            'proximos_monto'       => (float) $proximos->sum('pago'),
            'margen_dias'          => $margen,
        ];

        // Lista de los viajes vencidos (para mostrarlos en el dashboard).
        $viajesVencidos = $vencidos->load(['empresa', 'operador'])
            ->sortBy('fecha_limite_pago')
            ->take(5);

        // --- Alerta de documentos de unidades por vencer ---
        $docsVencidos = 0;
        $docsPorVencer = 0;
    
        foreach (DocumentoTracto::whereNotNull('fecha_vencimiento')->get() as $d) {
            if ($d->esta_vencido) $docsVencidos++;
            elseif ($d->esta_por_vencer) $docsPorVencer++;
        }
        foreach (DocumentoEquipo::whereNotNull('fecha_vencimiento')->get() as $d) {
            if ($d->esta_vencido) $docsVencidos++;
            elseif ($d->esta_por_vencer) $docsPorVencer++;
        }
    
        $alertaDocumentos = [
            'vencidos'   => $docsVencidos,
            'por_vencer' => $docsPorVencer,
        ];

        /* ---------------------------------------------------------
         | GRAFICA 1: viajes e ingresos por mes (ultimos 6 meses)
         |---------------------------------------------------------*/
        $desde = $hoy->copy()->subMonths(5)->startOfMonth();

        $porMes = Viaje::selectRaw('YEAR(fecha_viaje) anio, MONTH(fecha_viaje) mes,
                                    COUNT(*) total, SUM(pago) monto')
            ->where('fecha_viaje', '>=', $desde)
            ->groupBy('anio', 'mes')
            ->orderBy('anio')
            ->orderBy('mes')
            ->get()
            ->keyBy(fn ($fila) => $fila->anio . '-' . str_pad($fila->mes, 2, '0', STR_PAD_LEFT));

        $meses = [];
        $mesesViajes = [];
        $mesesMontos = [];

        $nombresMes = [
            1 => 'Ene', 2 => 'Feb', 3 => 'Mar', 4 => 'Abr', 5 => 'May', 6 => 'Jun',
            7 => 'Jul', 8 => 'Ago', 9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dic',
        ];

        for ($i = 5; $i >= 0; $i--) {
            $fecha = $hoy->copy()->subMonths($i);
            $clave = $fecha->format('Y-m');

            $meses[]       = $nombresMes[$fecha->month] . ' ' . $fecha->year;
            $mesesViajes[] = (int) ($porMes[$clave]->total ?? 0);
            $mesesMontos[] = (float) ($porMes[$clave]->monto ?? 0);
        }

        /* ---------------------------------------------------------
         | GRAFICA 2: viajes por empresa
         |---------------------------------------------------------*/
        $porEmpresa = Viaje::selectRaw('empresa_id, COUNT(*) total')
            ->with('empresa:id,nombre')
            ->groupBy('empresa_id')
            ->orderByDesc('total')
            ->limit(6)
            ->get();

        /* ---------------------------------------------------------
         | GRAFICA 3: viajes por estatus
         |---------------------------------------------------------*/
        $porEstatus = Viaje::selectRaw('estatus, COUNT(*) total')
            ->groupBy('estatus')
            ->pluck('total', 'estatus');

        /* ---------------------------------------------------------
         | TABLA: ultimos viajes registrados
         |---------------------------------------------------------*/
        $ultimosViajes = Viaje::with(['empresa', 'operador'])
            ->orderByDesc('fecha_viaje')
            ->orderByDesc('id')
            ->limit(8)
            ->get();

        // --- Tractos que requieren mantenimiento por km ---
        $tractosMantenimiento = Tracto::where('estatus', '!=', 'Baja')
            ->get()
            ->filter(fn ($t) => $t->requiere_mantenimiento)
            ->count();

        return view('dashboard.index', [
            'kpis'           => $kpis,
            'alertaDocumentos' => $alertaDocumentos,
            'alertasPago'    => $alertasPago,
            'viajesVencidos' => $viajesVencidos,
            'meses'          => $meses,
            'mesesViajes'    => $mesesViajes,
            'mesesMontos'    => $mesesMontos,
            'porEmpresa'     => $porEmpresa,
            'porEstatus'     => $porEstatus,
            'ultimosViajes'  => $ultimosViajes,
            'tractosMantenimiento' => $tractosMantenimiento,
        ]);
    }
}