<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tracto extends Model
{
    use HasFactory;

    protected $table = 'tractos';

    /** Kilómetros a los que se hace mantenimiento. */
    public const KM_MANTENIMIENTO = 30000;

    /** Kilómetros a los que salta la alarma de aviso. */
    public const KM_ALARMA = 20000;

    protected $fillable = [
        'serie',
        'placa',
        'marca',
        'modelo',
        'anio',
        'tarjeta_circulacion',
        'tarjeta_pdf',
        'estatus',
        'observaciones',
    ];

    /* ---------------- Relaciones ---------------- */

    public function viajes()
    {
        return $this->hasMany(Viaje::class);
    }

    public function documentos()
    {
        return $this->hasMany(DocumentoTracto::class)->latest();
    }

    public function mantenimientos()
    {
        return $this->hasMany(Mantenimiento::class);
    }

    /* ---------------- Scopes ---------------- */

    public function scopeBuscar($query, ?string $texto)
    {
        if (blank($texto)) {
            return $query;
        }

        return $query->where(function ($q) use ($texto) {
            $q->where('serie', 'like', "%{$texto}%")
              ->orWhere('placa', 'like', "%{$texto}%")
              ->orWhere('marca', 'like', "%{$texto}%")
              ->orWhere('modelo', 'like', "%{$texto}%");
        });
    }

    /* ---------------- Accesores ---------------- */

    public function getEtiquetaAttribute(): string
    {
        return $this->serie . ' - ' . $this->placa;
    }

    /* ---------------- Kilómetros y mantenimiento ---------------- */

    /**
     * Fecha del último mantenimiento de este tracto (o null si no tiene).
     * Se usa para saber desde cuándo contar los kilómetros.
     */
    public function getFechaUltimoMantenimientoAttribute()
    {
        return $this->mantenimientos()
            ->whereNotNull('fecha_entrada')
            ->orderByDesc('fecha_entrada')
            ->value('fecha_entrada');
    }

    /**
     * Kilómetros acumulados desde el último mantenimiento.
     * Suma los km de los viajes posteriores a esa fecha.
     * Si nunca ha tenido mantenimiento, suma todos sus viajes.
     */
    public function getKmAcumuladosAttribute(): int
    {
        $desde = $this->fecha_ultimo_mantenimiento;

        $query = $this->viajes()->whereNotNull('kilometros');

        if ($desde) {
            // Solo viajes posteriores (o del mismo día) al mantenimiento
            $query->whereDate('fecha_viaje', '>=', $desde);
        }

        return (int) $query->sum('kilometros');
    }

    /**
     * Kilómetros que faltan para llegar al mantenimiento (30,000).
     */
    public function getKmParaMantenimientoAttribute(): int
    {
        return self::KM_MANTENIMIENTO - $this->km_acumulados;
    }

    /**
     * ¿Ya alcanzó o pasó el umbral de alarma (20,000 km)?
     */
    public function getRequiereMantenimientoAttribute(): bool
    {
        return $this->km_acumulados >= self::KM_ALARMA;
    }

    /**
     * ¿Ya pasó el límite de mantenimiento (30,000 km)? (crítico)
     */
    public function getMantenimientoVencidoAttribute(): bool
    {
        return $this->km_acumulados >= self::KM_MANTENIMIENTO;
    }

    /**
     * Porcentaje de avance hacia el mantenimiento (para barra visual).
     */
    public function getPorcentajeMantenimientoAttribute(): int
    {
        $pct = ($this->km_acumulados / self::KM_MANTENIMIENTO) * 100;
        return (int) min(100, max(0, round($pct)));
    }
}