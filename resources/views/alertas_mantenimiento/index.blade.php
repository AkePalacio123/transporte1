@extends('layouts.app')

@section('title','Alertas de mantenimiento')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">
            <i class="bi bi-speedometer me-2"></i>Alertas de mantenimiento
        </h2>
    </div>

    <p class="text-muted">
        Kilómetros acumulados por cada tracto desde su último mantenimiento.
        Se avisa a los {{ number_format($kmAlarma) }} km, y el mantenimiento
        corresponde a los {{ number_format($kmLimite) }} km. Los kilómetros se
        suman de los viajes registrados posteriores al último mantenimiento.
    </p>

    @php
        // Función auxiliar inline para pintar la tarjeta de cada tracto
        $tarjeta = function($t, $colorBarra) use ($kmLimite) {
            return null; // (se usa el bloque de abajo, esto es solo referencia)
        };
    @endphp

    {{-- ============ CRÍTICOS (ya pasaron 30,000) ============ --}}
    @if($criticos->count() > 0)
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-danger text-white fw-bold">
                <i class="bi bi-exclamation-octagon-fill me-2"></i>
                Mantenimiento vencido ({{ $criticos->count() }})
            </div>
            <div class="card-body">
                @foreach($criticos as $t)
                    @include('alertas_mantenimiento._fila', ['t' => $t, 'color' => 'danger', 'kmLimite' => $kmLimite])
                @endforeach
            </div>
        </div>
    @endif

    {{-- ============ EN ALARMA (20,000 a 30,000) ============ --}}
    @if($enAlarma->count() > 0)
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-warning fw-bold">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                Requieren mantenimiento pronto ({{ $enAlarma->count() }})
            </div>
            <div class="card-body">
                @foreach($enAlarma as $t)
                    @include('alertas_mantenimiento._fila', ['t' => $t, 'color' => 'warning', 'kmLimite' => $kmLimite])
                @endforeach
            </div>
        </div>
    @endif

    {{-- ============ NORMALES ============ --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white fw-bold">
            <i class="bi bi-check-circle me-2"></i>
            Dentro del rango ({{ $normales->count() }})
        </div>
        <div class="card-body">
            @if($normales->count() > 0)
                @foreach($normales as $t)
                    @include('alertas_mantenimiento._fila', ['t' => $t, 'color' => 'success', 'kmLimite' => $kmLimite])
                @endforeach
            @else
                <div class="text-center text-muted py-3">
                    No hay tractos en esta categoría.
                </div>
            @endif
        </div>
    </div>

</div>
@endsection