<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class DocumentoTracto extends Model
{
    use HasFactory;

    protected $table = 'documentos_tracto';

    public const DIAS_AVISO_VENCIMIENTO = 30;

    public const CATEGORIAS = [
        'Póliza de seguro',
        'Tarjeta de circulación',
        'Verificación físico-mecánica',
        'Factura',
        'Permiso SCT / federal',
        'Otro',
    ];

    protected $fillable = [
        'tracto_id',
        'categoria',
        'nombre_archivo',
        'ruta',
        'fecha_vencimiento',
        'observaciones',
    ];

    protected $casts = [
        'fecha_vencimiento' => 'date',
    ];

    public function tracto()
    {
        return $this->belongsTo(Tracto::class);
    }

    public function getEstaVencidoAttribute(): bool
    {
        if (blank($this->fecha_vencimiento)) {
            return false;
        }
        return $this->fecha_vencimiento->isPast() && ! $this->fecha_vencimiento->isToday();
    }

    public function getEstaPorVencerAttribute(): bool
    {
        if (blank($this->fecha_vencimiento) || $this->esta_vencido) {
            return false;
        }
        $dias = Carbon::today()->diffInDays($this->fecha_vencimiento, false);
        return $dias >= 0 && $dias <= self::DIAS_AVISO_VENCIMIENTO;
    }

    public function getEsImagenAttribute(): bool
    {
        $ext = strtolower(pathinfo($this->ruta, PATHINFO_EXTENSION));
        return in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif']);
    }
}