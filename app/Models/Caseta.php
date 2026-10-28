<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Caseta extends Model
{
    use HasFactory;

    protected $table = 'casetas';

    protected $fillable = [
        'viaje_id',
        'nombre',
        'costo',
        'fecha',
        'folio_ticket',
        'observaciones',
    ];

    protected $casts = [
        'fecha' => 'date',
        'costo' => 'decimal:2',
    ];

    public function viaje()
    {
        return $this->belongsTo(Viaje::class);
    }

    /* ------------------------------------------------------------------
     | SCOPES (para el listado y la exportación del módulo de casetas)
     | -----------------------------------------------------------------*/

    /**
     * Búsqueda libre por nombre de caseta, folio de ticket,
     * o por el folio / carta porte del viaje al que pertenece.
     */
    public function scopeBuscar($query, ?string $texto)
    {
        if (blank($texto)) {
            return $query;
        }

        return $query->where(function ($q) use ($texto) {
            $q->where('nombre', 'like', "%{$texto}%")
              ->orWhere('folio_ticket', 'like', "%{$texto}%")
              ->orWhereHas('viaje', function ($v) use ($texto) {
                  $v->where('folio', 'like', "%{$texto}%")
                    ->orWhere('cataporte', 'like', "%{$texto}%");
              });
        });
    }

    /**
     * Filtra las casetas por la empresa del viaje.
     */
    public function scopeDeEmpresa($query, $empresaId)
    {
        if (blank($empresaId)) {
            return $query;
        }

        return $query->whereHas('viaje', function ($v) use ($empresaId) {
            $v->where('empresa_id', $empresaId);
        });
    }

    /**
     * Filtra las casetas por el operador del viaje.
     */
    public function scopeDeOperador($query, $operadorId)
    {
        if (blank($operadorId)) {
            return $query;
        }

        return $query->whereHas('viaje', function ($v) use ($operadorId) {
            $v->where('operador_id', $operadorId);
        });
    }

    /**
     * Filtra por estatus de pago del viaje (Pagado / Pendiente).
     */
    public function scopeConPagoViaje($query, $estatusPago)
    {
        if (blank($estatusPago)) {
            return $query;
        }

        return $query->whereHas('viaje', function ($v) use ($estatusPago) {
            $v->where('estatus_pago', $estatusPago);
        });
    }

    /**
     * Filtra por rango de fechas de la caseta.
     */
    public function scopeEntreFechas($query, $desde, $hasta)
    {
        if (filled($desde)) {
            $query->whereDate('fecha', '>=', $desde);
        }

        if (filled($hasta)) {
            $query->whereDate('fecha', '<=', $hasta);
        }

        return $query;
    }
}