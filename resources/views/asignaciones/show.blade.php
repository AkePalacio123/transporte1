@extends('layouts.app')
@section('title','Detalle de la asignación')
@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0"><i class="bi bi-diagram-3 me-2"></i>Asignación #{{ $asignacion->id }}</h2>
        <div class="d-flex gap-2">
            <a href="{{ route('asignaciones.edit',$asignacion) }}" class="btn btn-warning text-white">
                <i class="bi bi-pencil me-1"></i>Editar
            </a>
            <a href="{{ route('asignaciones.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Volver
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <dl class="row mb-0">
                <dt class="col-3">Operador</dt>
                <dd class="col-9">{{ $asignacion->operador->nombre ?? $asignacion->operador->nombre_completo ?? '—' }}</dd>

                <dt class="col-3">Tracto</dt>
                <dd class="col-9">{{ $asignacion->tracto->serie ?? '—' }} - {{ $asignacion->tracto->placa ?? '' }}</dd>

                <dt class="col-3">Equipo</dt>
                <dd class="col-9">
                    @if($asignacion->equipo)
                        {{ $asignacion->equipo->serie }} - {{ $asignacion->equipo->placa }}
                    @else — (sin equipo) @endif
                </dd>

                <dt class="col-3">Fecha de inicio</dt>
                <dd class="col-9">{{ optional($asignacion->fecha_inicio)->format('d/m/Y') }}</dd>

                <dt class="col-3">Fecha de fin</dt>
                <dd class="col-9">{{ optional($asignacion->fecha_fin)->format('d/m/Y') ?: '— (vigente)' }}</dd>

                <dt class="col-3">Estado</dt>
                <dd class="col-9">
                    @if($asignacion->activa)
                        <span class="badge bg-success">Activa</span>
                    @else
                        <span class="badge bg-secondary">Inactiva</span>
                    @endif
                </dd>

                @if($asignacion->observaciones)
                    <dt class="col-3">Observaciones</dt>
                    <dd class="col-9">{{ $asignacion->observaciones }}</dd>
                @endif
            </dl>
        </div>
    </div>

</div>
@endsection