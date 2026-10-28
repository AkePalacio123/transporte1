@extends('layouts.app')

@section('title','Calificación de operadores')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">
            <i class="bi bi-award me-2"></i>Calificación de operadores
        </h2>
    </div>

    <p class="text-muted">
        Rendimiento de cada operador en el mes seleccionado: ganancias contra gastos,
        rendimiento de diesel (mínimo {{ rtrim(rtrim(number_format($minimo,1),'0'),'.') }} km/L)
        y viajes finalizados. Sirve para decidir apoyos o recompensas con base en números.
    </p>

    {{-- ============ SELECTOR DE MES ============ --}}
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small mb-1">Mes</label>
                    <select name="mes" class="form-select">
                        @foreach($nombresMes as $num => $nombre)
                            <option value="{{ $num }}" @selected($mes == $num)>{{ $nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small mb-1">Año</label>
                    <select name="anio" class="form-select">
                        @foreach($anios as $a)
                            <option value="{{ $a }}" @selected($anio == $a)>{{ $a }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary w-100">
                        <i class="bi bi-search me-1"></i>Ver
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ============ TARJETAS DE OPERADORES ============ --}}
    @php $conViajes = $calificaciones->where('total_viajes', '>', 0); @endphp

    @if($conViajes->count() === 0)
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center text-muted py-5">
                <i class="bi bi-calendar-x d-block fs-1 mb-2"></i>
                Ningún operador tiene viajes registrados en {{ $nombresMes[$mes] }} {{ $anio }}.
            </div>
        </div>
    @else
        <div class="row g-3">
            @foreach($calificaciones as $c)
                @if($c['total_viajes'] > 0)
                    <div class="col-lg-6">
                        <div class="card border-0 shadow-sm h-100">

                            {{-- Encabezado con nombre y etiqueta --}}
                            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                <span class="fw-bold">
                                    <i class="bi bi-person-badge me-2"></i>{{ $c['operador']->nombre }}
                                </span>
                                <span class="badge bg-{{ $c['color'] }} fs-6">{{ $c['etiqueta'] }}</span>
                            </div>

                            <div class="card-body">

                                {{-- Números principales --}}
                                <div class="row text-center mb-3">
                                    <div class="col-4 border-end">
                                        <div class="text-muted small text-uppercase">Ganancias</div>
                                        <div class="fw-bold text-success">${{ number_format($c['ingresos'], 2) }}</div>
                                    </div>
                                    <div class="col-4 border-end">
                                        <div class="text-muted small text-uppercase">Gastos</div>
                                        <div class="fw-bold text-danger">${{ number_format($c['gastos'], 2) }}</div>
                                        <div class="text-muted" style="font-size:.7rem">
                                            Rep. ${{ number_format($c['reparaciones'], 0) }} ·
                                            Comb. ${{ number_format($c['combustible'], 0) }}
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="text-muted small text-uppercase">Resultado</div>
                                        <div class="fw-bold {{ $c['resultado'] >= 0 ? 'text-primary' : 'text-danger' }}">
                                            {{ $c['resultado'] >= 0 ? '+' : '' }}${{ number_format($c['resultado'], 2) }}
                                        </div>
                                    </div>
                                </div>

                                {{-- Rendimiento diesel destacado --}}
                                <div class="d-flex justify-content-between align-items-center bg-light rounded p-2 mb-3">
                                    <span class="small">
                                        <i class="bi bi-fuel-pump me-1"></i>Rendimiento diesel
                                    </span>
                                    <span class="fw-bold">
                                        @if($c['rendimiento'] !== null)
                                            {{ $c['rendimiento'] }} km/L
                                            @if($c['rendimiento'] >= $minimo)
                                                <i class="bi bi-check-circle-fill text-success ms-1"></i>
                                            @else
                                                <i class="bi bi-x-circle-fill text-danger ms-1"></i>
                                            @endif
                                        @else
                                            <span class="text-muted">Sin datos</span>
                                        @endif
                                    </span>
                                </div>

                                {{-- Desglose de criterios (el porqué) --}}
                                <div class="small">
                                    <div class="text-muted text-uppercase mb-1" style="font-size:.7rem">Detalle</div>
                                    @foreach($c['criterios'] as $criterio)
                                        <div class="d-flex align-items-start mb-1">
                                            @if($criterio['ok'] === true)
                                                <i class="bi bi-check-circle-fill text-success me-2 mt-1"></i>
                                            @elseif($criterio['ok'] === false)
                                                <i class="bi bi-exclamation-triangle-fill text-danger me-2 mt-1"></i>
                                            @else
                                                <i class="bi bi-dash-circle text-muted me-2 mt-1"></i>
                                            @endif
                                            <span>{{ $criterio['mensaje'] }}</span>
                                        </div>
                                    @endforeach
                                </div>

                            </div>

                            <div class="card-footer bg-white text-muted small">
                                {{ $c['viajes_finalizados'] }} de {{ $c['total_viajes'] }} viajes finalizados ·
                                {{ number_format($c['km_totales']) }} km ·
                                {{ number_format($c['litros_totales'], 0) }} L
                            </div>

                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    @endif

</div>
@endsection