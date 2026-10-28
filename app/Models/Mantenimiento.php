<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mantenimiento extends Model
{
    use HasFactory;

    protected $table = 'mantenimientos';

    protected $fillable = [
        'tracto_id',
        'equipo_id',
        'tipo',
        'estatus',
        'fecha_entrada',
        'fecha_salida',
        'kilometraje',
        'taller',
        'descripcion',
        'costo_mano_obra',
        'folio_factura',
        'factura_pdf',
        'observaciones',
    ];

    protected $casts = [
        'fecha_entrada'   => 'date',
        'fecha_salida'    => 'date',
        'costo_mano_obra' => 'decimal:2',
    ];

    /* ---------------- Relaciones ---------------- */

    public function tracto()
    {
        return $this->belongsTo(Tracto::class);
    }

    public function equipo()
    {
        return $this->belongsTo(Equipo::class);
    }

    public function piezas()
    {
        return $this->hasMany(PiezaMantenimiento::class)->orderBy('id');
    }

    /* ---------------- Scopes ---------------- */

    public function scopeBuscar($query, ?string $texto)
    {
        if (blank($texto)) {
            return $query;
        }

        return $query->where(function ($q) use ($texto) {
            $q->where('descripcion', 'like', "%{$texto}%")
              ->orWhere('taller', 'like', "%{$texto}%")
              ->orWhere('folio_factura', 'like', "%{$texto}%")
              ->orWhereHas('tracto', fn ($t) => $t->where('serie', 'like', "%{$texto}%")
                                                  ->orWhere('placa', 'like', "%{$texto}%"))
              ->orWhereHas('equipo', fn ($e) => $e->where('serie', 'like', "%{$texto}%")
                                                  ->orWhere('placa', 'like', "%{$texto}%"));
        });
    }

    public function scopeFiltrarPor($query, string $columna, $valor)
    {
        if (blank($valor)) {
            return $query;
        }

        return $query->where($columna, $valor);
    }

    public function scopeEntreFechas($query, $desde, $hasta)
    {
        if (filled($desde)) {
            $query->whereDate('fecha_entrada', '>=', $desde);
        }

        if (filled($hasta)) {
            $query->whereDate('fecha_entrada', '<=', $hasta);
        }

        return $query;
    }

    /**
     * Filtra por unidad: 'tracto-3' o 'equipo-5'.
     */
    public function scopeDeUnidad($query, ?string $unidad)
    {
        if (blank($unidad) || ! str_contains($unidad, '-')) {
            return $query;
        }

        [$tipo, $id] = explode('-', $unidad, 2);

        return $tipo === 'tracto'
            ? $query->where('tracto_id', $id)
            : $query->where('equipo_id', $id);
    }

    /* ---------------- Accesores ---------------- */

    /**
     * Devuelve el modelo de la unidad atendida (tracto o equipo).
     */
    public function getUnidadAttribute()
    {
        return $this->tracto ?? $this->equipo;
    }

    public function getTipoUnidadAttribute(): string
    {
        return $this->tracto_id ? 'Tracto' : 'Equipo';
    }

    public function getUnidadEtiquetaAttribute(): string
    {
        $unidad = $this->unidad;

        return $unidad
            ? $this->tipo_unidad . ' ' . $unidad->serie
            : 'Sin unidad';
    }

    public function getTotalPiezasAttribute(): float
    {
        return (float) $this->piezas->sum('subtotal');
    }

    public function getCostoTotalAttribute(): float
    {
        return (float) $this->costo_mano_obra + $this->total_piezas;
    }

    public function getDiasEnTallerAttribute(): ?int
    {
        if (! $this->fecha_salida) {
            return null;
        }

        return $this->fecha_entrada->diffInDays($this->fecha_salida);
    }
}