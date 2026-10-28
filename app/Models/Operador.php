<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Operador extends Model
{
    use HasFactory;

    protected $table = 'operadores';

    /** Rendimiento mínimo de diesel exigido por la empresa (km por litro). */
    public const RENDIMIENTO_MINIMO = 2.5;

    /** Las reparaciones no deben superar este % de las ganancias. */
    public const UMBRAL_REPARACIONES = 0.50; // 50%

    protected $fillable = [
        'nombre',
        'telefono',
        'licencia',
        'vencimiento_licencia',
        'estatus'
    ];

    /*
    |--------------------------------------------------------------------------
    | Relaciones
    |--------------------------------------------------------------------------
    */

    public function viajes()
    {
        return $this->hasMany(Viaje::class);
    }

    public function asignacion()
    {
        return $this->hasOne(Asignacion::class);
    }

    public function documentos()
    {
        return $this->hasMany(DocumentoOperador::class)->latest();
    }

    /*
    |--------------------------------------------------------------------------
    | Calificación de rendimiento (por mes)
    |--------------------------------------------------------------------------
    */

    /**
     * Calcula el rendimiento del operador en un mes/año dados.
     * Devuelve un arreglo con todos los números y la evaluación.
     *
     * @param int $anio  Ej. 2026
     * @param int $mes   1-12
     */
    public function rendimientoDelMes(int $anio, int $mes): array
    {
        // Viajes del operador en ese mes
        $viajes = $this->viajes()
            ->whereYear('fecha_viaje', $anio)
            ->whereMonth('fecha_viaje', $mes)
            ->with(['cargasCombustible', 'reparaciones'])
            ->get();

        // --- Conteos y sumas ---
        $totalViajes     = $viajes->count();
        $viajesFinalizados = $viajes->where('estatus', 'Finalizado')->count();

        $ingresos     = (float) $viajes->sum('pago');
        $reparaciones = (float) $viajes->sum(fn ($v) => $v->reparaciones->sum('costo'));
        $combustible  = (float) $viajes->sum(fn ($v) => $v->cargasCombustible->sum('total'));

        $gastos    = $reparaciones + $combustible;
        $resultado = $ingresos - $gastos;

        // --- Rendimiento de diesel (global del mes) ---
        // Solo viajes que tengan km capturado cuentan para km y litros.
        $viajesConKm = $viajes->filter(fn ($v) => ! blank($v->kilometros) && $v->kilometros > 0);

        $kmTotales     = (int) $viajesConKm->sum('kilometros');
        $litrosTotales = (float) $viajesConKm->sum(fn ($v) => $v->cargasCombustible->sum('litros'));

        $rendimiento = ($litrosTotales > 0)
            ? round($kmTotales / $litrosTotales, 2)
            : null; // null = no se puede calcular (sin datos)

        // --- Evaluación por criterios ---
        $criterios = [];

        // 1. Diesel
        if ($rendimiento === null) {
            $criterios['diesel'] = [
                'ok'      => null,
                'mensaje' => 'Sin datos suficientes de kilómetros o litros para calcular el rendimiento.',
            ];
        } elseif ($rendimiento >= self::RENDIMIENTO_MINIMO) {
            $criterios['diesel'] = [
                'ok'      => true,
                'mensaje' => "Buen rendimiento de diesel: {$rendimiento} km/L (mínimo {$this->minimoTexto()}).",
            ];
        } else {
            $criterios['diesel'] = [
                'ok'      => false,
                'mensaje' => "Rendimiento de diesel bajo: {$rendimiento} km/L (por debajo del mínimo {$this->minimoTexto()}).",
            ];
        }

        // 2. Reparaciones vs ganancias
        if ($ingresos <= 0) {
            $criterios['reparaciones'] = [
                'ok'      => null,
                'mensaje' => 'Sin ingresos registrados en el mes para comparar reparaciones.',
            ];
        } else {
            $porcentaje = $reparaciones / $ingresos;
            $pctTexto   = round($porcentaje * 100);

            if ($porcentaje <= self::UMBRAL_REPARACIONES) {
                $criterios['reparaciones'] = [
                    'ok'      => true,
                    'mensaje' => "Reparaciones bajo control: representan el {$pctTexto}% de las ganancias.",
                ];
            } else {
                $criterios['reparaciones'] = [
                    'ok'      => false,
                    'mensaje' => "Reparaciones elevadas: representan el {$pctTexto}% de las ganancias (más del 50%).",
                ];
            }
        }

        // 3. Viajes finalizados
        if ($totalViajes === 0) {
            $criterios['viajes'] = [
                'ok'      => null,
                'mensaje' => 'No hay viajes registrados en el mes.',
            ];
        } else {
            $todosFinalizados = $viajesFinalizados === $totalViajes;
            $criterios['viajes'] = [
                'ok'      => $todosFinalizados,
                'mensaje' => "{$viajesFinalizados} de {$totalViajes} viajes finalizados.",
            ];
        }

        // --- Etiqueta global según cuántos criterios se cumplen ---
        $cumplidos = collect($criterios)->where('ok', true)->count();
        $evaluables = collect($criterios)->whereNotNull('ok')->count();

        if ($evaluables === 0) {
            $etiqueta = 'Sin datos';
            $color    = 'secondary';
        } elseif ($cumplidos === $evaluables) {
            $etiqueta = 'Buen rendimiento';
            $color    = 'success';
        } elseif ($cumplidos === 0) {
            $etiqueta = 'Mal rendimiento';
            $color    = 'danger';
        } else {
            $etiqueta = 'Rendimiento regular';
            $color    = 'warning';
        }

        return [
            'operador'           => $this,
            'total_viajes'       => $totalViajes,
            'viajes_finalizados' => $viajesFinalizados,
            'ingresos'           => $ingresos,
            'reparaciones'       => $reparaciones,
            'combustible'        => $combustible,
            'gastos'             => $gastos,
            'resultado'          => $resultado,
            'km_totales'         => $kmTotales,
            'litros_totales'     => $litrosTotales,
            'rendimiento'        => $rendimiento,
            'criterios'          => $criterios,
            'etiqueta'           => $etiqueta,
            'color'              => $color,
        ];
    }

    private function minimoTexto(): string
    {
        return rtrim(rtrim(number_format(self::RENDIMIENTO_MINIMO, 1), '0'), '.') . ' km/L';
    }
}