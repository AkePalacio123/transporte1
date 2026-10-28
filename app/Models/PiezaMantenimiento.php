<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PiezaMantenimiento extends Model
{
    use HasFactory;

    protected $table = 'piezas_mantenimiento';

    protected $fillable = [
        'mantenimiento_id',
        'nombre',
        'numero_parte',
        'proveedor',
        'cantidad',
        'precio_unitario',
        'subtotal',
    ];

    protected $casts = [
        'cantidad'        => 'decimal:2',
        'precio_unitario' => 'decimal:2',
        'subtotal'        => 'decimal:2',
    ];

    public function mantenimiento()
    {
        return $this->belongsTo(Mantenimiento::class);
    }

    /**
     * El subtotal se recalcula solo al guardar.
     */
    protected static function booted(): void
    {
        static::saving(function (PiezaMantenimiento $pieza) {
            $pieza->subtotal = round($pieza->cantidad * $pieza->precio_unitario, 2);
        });
    }
}