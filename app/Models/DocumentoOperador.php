<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class DocumentoOperador extends Model
{
    use HasFactory;

    protected $table = 'documentos_operador';

    /** Días de margen para avisar que un documento está por vencer. */
    public const DIAS_AVISO_VENCIMIENTO = 30;

    /** Categorías permitidas (lista fija). */
    public const CATEGORIAS = [
        'Licencia federal',
        'INE / Identificación',
        'RFC',
        'CURP',
        'Comprobante de domicilio',
        'Seguro médico',
        'Certificado médico',
        'Otro',
    ];

    protected $fillable = [
        'operador_id',
        'categoria',
        'nombre_archivo',
        'ruta',
        'fecha_vencimiento',
        'observaciones',
    ];

    protected $casts = [
        'fecha_vencimiento' => 'date',
    ];

    /* ------------------------------------------------------------------
     | RELACIONES
     | -----------------------------------------------------------------*/

    public function operador()
    {
        return $this->belongsTo(Operador::class);
    }

    /* ------------------------------------------------------------------
     | ACCESORES
     | -----------------------------------------------------------------*/

    /**
     * ¿El documento ya venció?
     */
    public function getEstaVencidoAttribute(): bool
    {
        if (blank($this->fecha_vencimiento)) {
            return false;
        }

        return $this->fecha_vencimiento->isPast()
            && ! $this->fecha_vencimiento->isToday();
    }

    /**
     * ¿Está por vencer (dentro de los próximos 30 días)?
     */
    public function getEstaPorVencerAttribute(): bool
    {
        if (blank($this->fecha_vencimiento) || $this->esta_vencido) {
            return false;
        }

        $dias = Carbon::today()->diffInDays($this->fecha_vencimiento, false);

        return $dias >= 0 && $dias <= self::DIAS_AVISO_VENCIMIENTO;
    }

    /**
     * Icono según el tipo de archivo (para la vista).
     */
    public function getEsImagenAttribute(): bool
    {
        $ext = strtolower(pathinfo($this->ruta, PATHINFO_EXTENSION));
        return in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif']);
    }
}