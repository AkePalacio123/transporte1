<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipo extends Model
{
    use HasFactory;

    protected $table = 'equipos';

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

    // anio es tipo YEAR en la BD: se maneja como entero, sin cast de fecha.

    /* ---------------- Relaciones ---------------- */

    public function viajes()
    {
        return $this->hasMany(Viaje::class);
    }

    public function documentos()
    {
        return $this->hasMany(DocumentoEquipo::class)->latest();
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
}