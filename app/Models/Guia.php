<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guia extends Model
{
    use HasFactory;

    protected $table = 'guias';

    public const CATEGORIAS = [
        'Facturación',
        'Trámites y licencias',
        'Operación',
        'Administrativo',
        'Otro',
    ];

    protected $fillable = [
        'titulo',
        'categoria',
        'descripcion',
        'nombre_archivo',
        'ruta',
    ];

    /* ---------------- Scopes ---------------- */

    public function scopeBuscar($query, ?string $texto)
    {
        if (blank($texto)) {
            return $query;
        }

        return $query->where(function ($q) use ($texto) {
            $q->where('titulo', 'like', "%{$texto}%")
              ->orWhere('descripcion', 'like', "%{$texto}%")
              ->orWhere('categoria', 'like', "%{$texto}%");
        });
    }

    public function scopeFiltrarCategoria($query, ?string $categoria)
    {
        if (blank($categoria)) {
            return $query;
        }

        return $query->where('categoria', $categoria);
    }

    /* ---------------- Accesores ---------------- */

    public function getEsImagenAttribute(): bool
    {
        $ext = strtolower(pathinfo($this->ruta, PATHINFO_EXTENSION));
        return in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif']);
    }
}