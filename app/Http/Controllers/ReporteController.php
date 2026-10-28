<?php

namespace App\Http\Controllers;

use App\Models\Mantenimiento;
use App\Models\Viaje;
use App\Models\Caseta;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReporteController extends Controller
{
    /**
     * Exporta los viajes (con los filtros aplicados) a CSV.
     */
    public function viajes(Request $request): StreamedResponse
    {
        $viajes = Viaje::with(['empresa', 'operador', 'tracto', 'equipo'])
            ->buscar($request->buscar)
            ->filtrarPor('empresa_id', $request->empresa_id)
            ->filtrarPor('operador_id', $request->operador_id)
            ->filtrarPor('estatus', $request->estatus)
            ->filtrarPor('estatus_pago', $request->estatus_pago)
            ->filtrarPor('tipo_viaje', $request->tipo_viaje)
            ->entreFechas($request->desde, $request->hasta)
            ->orderByDesc('fecha_viaje')
            ->get();

        $columnas = [
            'Folio', 'Carta porte', 'Empresa', 'Tipo', 'Fecha viaje',
            'Origen', 'Destino', 'Operador', 'Tracto', 'Equipo',
            'Monto', 'Estatus', 'Estatus pago', 'Fecha pago', 'Bitacora',
        ];

        $filas = $viajes->map(function ($v) {
            return [
                $v->folio,
                $v->cataporte,
                $v->empresa->nombre ?? '',
                $v->tipo_viaje,
                optional($v->fecha_viaje)->format('d/m/Y'),
                $v->origen,
                $v->destino,
                $v->operador->nombre ?? $v->operador->nombre_completo ?? '',
                $v->tracto->serie ?? '',
                $v->equipo->serie ?? '',
                number_format($v->pago, 2, '.', ''),
                $v->estatus,
                $v->estatus_pago,
                optional($v->fecha_pago)->format('d/m/Y'),
                $v->estatus_bitacora,
            ];
        });

        return $this->generarCsv('viajes', $columnas, $filas);
    }

    /**
     * Exporta los mantenimientos (con filtros) a CSV.
     */
    public function mantenimientos(Request $request): StreamedResponse
    {
        $mantenimientos = Mantenimiento::with(['tracto', 'equipo', 'piezas'])
            ->buscar($request->buscar)
            ->filtrarPor('tipo', $request->tipo)
            ->filtrarPor('estatus', $request->estatus)
            ->deUnidad($request->unidad)
            ->entreFechas($request->desde, $request->hasta)
            ->orderByDesc('fecha_entrada')
            ->get();

        $columnas = [
            'Unidad', 'Tipo unidad', 'Tipo servicio', 'Estatus',
            'Fecha entrada', 'Fecha salida', 'Kilometraje', 'Taller',
            'Descripcion', 'Mano de obra', 'Total piezas', 'Costo total',
            'Folio factura',
        ];

        $filas = $mantenimientos->map(function ($m) {
            return [
                $m->unidad->serie ?? '',
                $m->tipo_unidad,
                $m->tipo,
                $m->estatus,
                optional($m->fecha_entrada)->format('d/m/Y'),
                optional($m->fecha_salida)->format('d/m/Y'),
                $m->kilometraje,
                $m->taller,
                $m->descripcion,
                number_format($m->costo_mano_obra, 2, '.', ''),
                number_format($m->total_piezas, 2, '.', ''),
                number_format($m->costo_total, 2, '.', ''),
                $m->folio_factura,
            ];
        });

        return $this->generarCsv('mantenimientos', $columnas, $filas);
    }

    /**
     * Exporta las casetas (con filtros) a CSV.
     */
    public function casetas(Request $request): StreamedResponse
    {
        $casetas = Caseta::with(['viaje.empresa', 'viaje.operador'])
            ->buscar($request->buscar)
            ->deEmpresa($request->empresa_id)
            ->deOperador($request->operador_id)
            ->conPagoViaje($request->estatus_pago)
            ->entreFechas($request->desde, $request->hasta)
            ->orderByDesc('fecha')
            ->get();
 
        $columnas = [
            'Viaje (Folio/CP)', 'Empresa', 'Operador', 'Caseta',
            'Fecha', 'Ticket', 'Costo', 'Estatus pago viaje',
        ];
 
        $filas = $casetas->map(function ($c) {
            return [
                $c->viaje->folio ?: $c->viaje->cataporte ?? '',
                $c->viaje->empresa->nombre ?? '',
                $c->viaje->operador->nombre ?? $c->viaje->operador->nombre_completo ?? '',
                $c->nombre,
                optional($c->fecha)->format('d/m/Y'),
                $c->folio_ticket,
                number_format($c->costo, 2, '.', ''),
                $c->viaje->estatus_pago ?? '',
            ];
        });
 
        return $this->generarCsv('casetas', $columnas, $filas);
    }

    /**
     * Genera y transmite el archivo CSV.
     */
    private function generarCsv(string $nombre, array $columnas, $filas): StreamedResponse
    {
        $archivo = $nombre . '_' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$archivo}\"",
        ];

        return response()->stream(function () use ($columnas, $filas) {
            $salida = fopen('php://output', 'w');

            // BOM para que Excel abra los acentos correctamente.
            fprintf($salida, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($salida, $columnas);

            foreach ($filas as $fila) {
                fputcsv($salida, $fila);
            }

            fclose($salida);
        }, 200, $headers);
    }
}