<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asignacion extends Model
{
    use HasFactory;

    protected $table = 'asignaciones';

    protected $fillable = [
        'operador_id',
        'tracto_id',
        'equipo_id',
        'fecha_inicio',
        'fecha_fin',
        'activa',
        'observaciones',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin'    => 'date',
        'activa'       => 'boolean',
    ];

    /* ---------------- Relaciones ---------------- */

    public function operador()
    {
        return $this->belongsTo(Operador::class);
    }

    public function tracto()
    {
        return $this->belongsTo(Tracto::class);
    }

    public function equipo()
    {
        return $this->belongsTo(Equipo::class);
    }

    /* ---------------- Scopes ---------------- */

    public function scopeActivas($query)
    {
        return $query->where('activa', true);
    }

    public function scopeBuscar($query, ?string $texto)
    {
        if (blank($texto)) {
            return $query;
        }

        return $query->where(function ($q) use ($texto) {
            $q->whereHas('operador', fn ($o) => $o->where('nombre', 'like', "%{$texto}%"))
              ->orWhereHas('tracto', fn ($t) => $t->where('serie', 'like', "%{$texto}%")
                                                  ->orWhere('placa', 'like', "%{$texto}%"))
              ->orWhereHas('equipo', fn ($e) => $e->where('serie', 'like', "%{$texto}%")
                                                  ->orWhere('placa', 'like', "%{$texto}%"));
        });
    }

    /* ---------------- Accesores ---------------- */

    public function getVigenteAttribute(): bool
    {
        return $this->activa && is_null($this->fecha_fin);
    }
}