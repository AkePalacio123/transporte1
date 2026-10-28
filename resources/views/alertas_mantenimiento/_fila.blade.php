{{-- Fila de un tracto con su barra de progreso de kilómetros --}}
<div class="d-flex align-items-center justify-content-between flex-wrap mb-3 pb-3 border-bottom">

    <div style="min-width: 200px;">
        <span class="fw-semibold">{{ $t->serie }}</span>
        <small class="text-muted d-block">{{ $t->placa }} · {{ $t->marca }} {{ $t->modelo }}</small>
        @if($t->fecha_ultimo_mantenimiento)
            <small class="text-muted">
                Últ. mantenimiento: {{ \Carbon\Carbon::parse($t->fecha_ultimo_mantenimiento)->format('d/m/Y') }}
            </small>
        @else
            <small class="text-muted fst-italic">Sin mantenimientos registrados</small>
        @endif
    </div>

    <div class="flex-grow-1 mx-3" style="min-width: 200px;">
        <div class="d-flex justify-content-between small mb-1">
            <span>{{ number_format($t->km_acumulados) }} km</span>
            <span class="text-muted">de {{ number_format($kmLimite) }} km</span>
        </div>
        <div class="progress" style="height: 10px;">
            <div class="progress-bar bg-{{ $color }}"
                 role="progressbar"
                 style="width: {{ $t->porcentaje_mantenimiento }}%">
            </div>
        </div>
    </div>

    <div class="text-end" style="min-width: 160px;">
        @if($t->mantenimiento_vencido)
            <span class="badge bg-danger">
                <i class="bi bi-exclamation-octagon-fill me-1"></i>
                Vencido por {{ number_format($t->km_acumulados - $kmLimite) }} km
            </span>
        @elseif($t->requiere_mantenimiento)
            <span class="badge bg-warning text-dark">
                <i class="bi bi-exclamation-triangle me-1"></i>
                Faltan {{ number_format($t->km_para_mantenimiento) }} km
            </span>
        @else
            <span class="badge bg-success">
                Faltan {{ number_format($t->km_para_mantenimiento) }} km
            </span>
        @endif
        <a href="{{ route('tractos.show', $t) }}" class="btn btn-sm btn-outline-secondary ms-1" title="Ver tracto">
            <i class="bi bi-eye"></i>
        </a>
    </div>

</div>