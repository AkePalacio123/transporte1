<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Viaje extends Model
{
    use HasFactory;

    protected $table = 'viajes';

    /** Días de margen para considerar un pago "próximo a vencer". */
    public const DIAS_AVISO_VENCIMIENTO = 7;

    /**
     * Campos que se pueden llenar masivamente desde un formulario.
     */
    protected $fillable = [
        'folio',
        'cataporte',
        'empresa_id',
        'tracto_id',
        'equipo_id',
        'operador_id',
        'tipo_viaje',
        'fecha_viaje',
        'fecha_captura',
        'fecha_pago',
        'fecha_limite_pago',
        'origen',
        'destino',
        'pago',
        'estatus',
        'estatus_pago',
        'estatus_bitacora',
        'entregado_a',
        'fecha_entrega_bitacora',
        'observaciones',
        'kilometros',
        'archivo_bitacora',
    ];

    /**
     * Conversion automatica de tipos al leer y escribir.
     */
    protected $casts = [
        'fecha_viaje'            => 'date',
        'fecha_captura'          => 'date',
        'fecha_pago'             => 'date',
        'fecha_limite_pago'      => 'date',
        'fecha_entrega_bitacora' => 'date',
        'pago'                   => 'decimal:2',
    ];

    /* ------------------------------------------------------------------
     | RELACIONES
     | -----------------------------------------------------------------*/

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function tracto()
    {
        return $this->belongsTo(Tracto::class);
    }

    public function equipo()
    {
        return $this->belongsTo(Equipo::class);
    }

    public function operador()
    {
        return $this->belongsTo(Operador::class);
    }

    public function casetas()
    {
        return $this->hasMany(Caseta::class)->orderBy('fecha')->orderBy('id');
    }

    public function cargasCombustible()
    {
        return $this->hasMany(CargaCombustible::class)->orderBy('fecha')->orderBy('id');
    }

    public function reparaciones()
    {
        return $this->hasMany(Reparacion::class)->orderBy('fecha')->orderBy('id');
    }

    /* ------------------------------------------------------------------
     | SCOPES (filtros reutilizables para el buscador)
     | -----------------------------------------------------------------*/

    /**
     * Busqueda libre por folio, cataporte, origen o destino.
     */
    public function scopeBuscar($query, ?string $texto)
    {
        if (blank($texto)) {
            return $query;
        }

        return $query->where(function ($q) use ($texto) {
            $q->where('folio', 'like', "%{$texto}%")
              ->orWhere('cataporte', 'like', "%{$texto}%")
              ->orWhere('origen', 'like', "%{$texto}%")
              ->orWhere('destino', 'like', "%{$texto}%");
        });
    }

    /**
     * Filtra por una columna solo si el valor viene definido.
     */
    public function scopeFiltrarPor($query, string $columna, $valor)
    {
        if (blank($valor)) {
            return $query;
        }

        return $query->where($columna, $valor);
    }

    /**
     * Filtra por rango de fechas de viaje.
     */
    public function scopeEntreFechas($query, $desde, $hasta)
    {
        if (filled($desde)) {
            $query->whereDate('fecha_viaje', '>=', $desde);
        }

        if (filled($hasta)) {
            $query->whereDate('fecha_viaje', '<=', $hasta);
        }

        return $query;
    }

    /**
     * Solo viajes con el pago aún pendiente (y no cancelados).
     * Lo usa el dashboard para calcular las alertas.
     */
    public function scopePagosPendientes($query)
    {
        return $query->where('estatus_pago', 'Pendiente')
                     ->where('estatus', '!=', 'Cancelado');
    }

    /**
     * Filtra por estado de alerta de pago: 'vencido' o 'proximo'.
     * Lo usa el filtro del listado de viajes.
     */
    public function scopeConAlerta($query, ?string $tipo)
    {
        if (blank($tipo)) {
            return $query;
        }
 
        $hoy    = \Illuminate\Support\Carbon::today();
        $margen = self::DIAS_AVISO_VENCIMIENTO;
 
        // Solo aplica a viajes con pago pendiente y fecha límite definida.
        $query->pagosPendientes()->whereNotNull('fecha_limite_pago');
 
        if ($tipo === 'vencido') {
            // La fecha límite ya pasó.
            $query->whereDate('fecha_limite_pago', '<', $hoy);
        }
 
        if ($tipo === 'proximo') {
            // La fecha límite está dentro de los próximos 'margen' días.
            $query->whereDate('fecha_limite_pago', '>=', $hoy)
                  ->whereDate('fecha_limite_pago', '<=', $hoy->copy()->addDays($margen));
        }
 
        return $query;
    }

    /* ------------------------------------------------------------------
     | ACCESORES (datos calculados para las vistas)
     | -----------------------------------------------------------------*/

    /**
     * Muestra el folio o, si la empresa no usa folio, el cataporte.
     */
    public function getIdentificadorAttribute(): string
    {
        return $this->folio ?: $this->cataporte;
    }

    public function getEstaPagadoAttribute(): bool
    {
        return $this->estatus_pago === 'Pagado';
    }

    public function getTieneBitacoraAttribute(): bool
    {
        return ! empty($this->archivo_bitacora);
    }

    public function getTotalCasetasAttribute(): float
    {
        return (float) $this->casetas->sum('costo');
    }

    public function getTotalReparacionesAttribute(): float
    {
        return (float) $this->reparaciones->sum('costo');
    }

    public function getTotalCombustibleAttribute(): float
    {
        return (float) $this->cargasCombustible->sum('total');
    }

    /* ------------------------------------------------------------------
     | ALERTAS DE PAGO
     | -----------------------------------------------------------------*/

    /**
     * ¿El pago está vencido?
     * Sí cuando: hay fecha límite, ya pasó, y sigue Pendiente (no cancelado).
     */
    public function getEstaVencidoAttribute(): bool
    {
        if ($this->esta_pagado || $this->estatus === 'Cancelado') {
            return false;
        }

        if (blank($this->fecha_limite_pago)) {
            return false;
        }

        return $this->fecha_limite_pago->isPast()
            && ! $this->fecha_limite_pago->isToday();
    }

    /**
     * ¿El pago está próximo a vencer?
     * Sí cuando: sigue Pendiente y faltan entre 0 y 7 días para el límite.
     */
    public function getEstaProximoVencerAttribute(): bool
    {
        if ($this->esta_pagado || $this->estatus === 'Cancelado') {
            return false;
        }

        if (blank($this->fecha_limite_pago)) {
            return false;
        }

        // Ya vencido no cuenta como "próximo".
        if ($this->esta_vencido) {
            return false;
        }

        $dias = Carbon::today()->diffInDays($this->fecha_limite_pago, false);

        return $dias >= 0 && $dias <= self::DIAS_AVISO_VENCIMIENTO;
    }

    /**
     * Días que faltan para la fecha límite (negativo si ya venció).
     * Útil para mostrar "faltan 3 días" o "venció hace 2 días".
     */
    public function getDiasParaVencerAttribute(): ?int
    {
        if (blank($this->fecha_limite_pago)) {
            return null;
        }

        return Carbon::today()->diffInDays($this->fecha_limite_pago, false);
    }

    /**
     * Estado de pago para pintar la fila:
     * 'vencido', 'proximo', 'pagado' o 'normal'.
     */
    public function getEstadoAlertaAttribute(): string
    {
        if ($this->esta_pagado) {
            return 'pagado';
        }
        if ($this->esta_vencido) {
            return 'vencido';
        }
        if ($this->esta_proximo_vencer) {
            return 'proximo';
        }
        return 'normal';
    }
}