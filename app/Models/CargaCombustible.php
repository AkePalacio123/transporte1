<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CargaCombustible extends Model
{
    use HasFactory;

    protected $table = 'cargas_combustible';

    protected $fillable = [
        'viaje_id',
        'estacion',
        'fecha',
        'litros',
        'precio_litro',
        'total',
        'kilometraje',
        'folio_factura',
        'observaciones',
    ];

    protected $casts = [
        'fecha'        => 'date',
        'litros'       => 'decimal:2',
        'precio_litro' => 'decimal:2',
        'total'        => 'decimal:2',
    ];

    public function viaje()
    {
        return $this->belongsTo(Viaje::class);
    }
}