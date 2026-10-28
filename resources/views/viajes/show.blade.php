@extends('layouts.app')

@section('title','Detalle del viaje')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">
            <i class="bi bi-signpost me-2"></i>
            Viaje {{ $viaje->identificador }}
        </h2>
        <div class="d-flex gap-2">
            <a href="{{ route('viajes.edit',$viaje) }}" class="btn btn-warning text-white">
                <i class="bi bi-pencil me-1"></i>Editar
            </a>
            <a href="{{ route('viajes.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Volver
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row g-3">

        {{-- ===== IDENTIFICACIÓN ===== --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white fw-bold">
                    <i class="bi bi-hash me-2"></i>Identificación
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-5">Empresa</dt>
                        <dd class="col-7">{{ $viaje->empresa->nombre ?? '—' }}</dd>

                        <dt class="col-5">Folio</dt>
                        <dd class="col-7">{{ $viaje->folio ?: '— (no aplica)' }}</dd>

                        <dt class="col-5">Carta porte</dt>
                        <dd class="col-7">{{ $viaje->cataporte }}</dd>

                        <dt class="col-5">Tipo</dt>
                        <dd class="col-7">{{ $viaje->tipo_viaje }}</dd>

                        <dt class="col-5">Fecha del viaje</dt>
                        <dd class="col-7">{{ optional($viaje->fecha_viaje)->format('d/m/Y') }}</dd>

                        <dt class="col-5">Fecha de captura</dt>
                        <dd class="col-7">{{ optional($viaje->fecha_captura)->format('d/m/Y') }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        {{-- ===== ASIGNACIÓN Y RUTA ===== --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white fw-bold">
                    <i class="bi bi-people me-2"></i>Asignación y ruta
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-5">Operador</dt>
                        <dd class="col-7">
                            {{ $viaje->operador->nombre ?? $viaje->operador->nombre_completo ?? '—' }}
                        </dd>

                        <dt class="col-5">Tracto</dt>
                        <dd class="col-7">
                            {{ $viaje->tracto->serie ?? '—' }}
                        </dd>

                        <dt class="col-5">Equipo</dt>
                        <dd class="col-7">
                            {{ $viaje->equipo->serie ?? '— (sin equipo)' }}
                        </dd>

                        <dt class="col-5">Origen</dt>
                        <dd class="col-7">{{ $viaje->origen }}</dd>

                        <dt class="col-5">Destino</dt>
                        <dd class="col-7">{{ $viaje->destino }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        {{-- ===== PAGO ===== --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white fw-bold">
                    <i class="bi bi-cash-coin me-2"></i>Pago
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <span class="fs-3 fw-bold">${{ number_format($viaje->pago, 2) }}</span>
                    </div>
                    <dl class="row mb-0">
                        <dt class="col-5">Estatus del viaje</dt>
                        <dd class="col-7">{{ $viaje->estatus }}</dd>

                        <dt class="col-5">Estatus de pago</dt>
                        <dd class="col-7">
                            <span class="badge bg-{{ $viaje->esta_pagado ? 'success' : 'warning text-dark' }}">
                                {{ $viaje->estatus_pago }}
                            </span>
                        </dd>

                        <dt class="col-5">Fecha de pago</dt>
                        <dd class="col-7">
                            {{ optional($viaje->fecha_pago)->format('d/m/Y') ?: '—' }}
                        </dd>
                    </dl>
                </div>
            </div>
        </div>

        {{-- ===== BITÁCORA ===== --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white fw-bold">
                    <i class="bi bi-file-earmark-pdf me-2"></i>Bitácora
                </div>
                <div class="card-body">
                    <dl class="row">
                        <dt class="col-5">Estatus</dt>
                        <dd class="col-7">{{ $viaje->estatus_bitacora }}</dd>

                        <dt class="col-5">Entregado a</dt>
                        <dd class="col-7">{{ $viaje->entregado_a ?: '—' }}</dd>

                        <dt class="col-5">Fecha de entrega</dt>
                        <dd class="col-7">
                            {{ optional($viaje->fecha_entrega_bitacora)->format('d/m/Y') ?: '—' }}
                        </dd>
                    </dl>

                    @if($viaje->tiene_bitacora)
                        <a href="{{ asset('storage/'.$viaje->archivo_bitacora) }}"
                           target="_blank" class="btn btn-outline-danger btn-sm">
                            <i class="bi bi-file-earmark-pdf me-1"></i>Ver PDF
                        </a>
                    @else
                        <span class="text-muted small">Sin archivo adjunto.</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- ===== OBSERVACIONES ===== --}}
        @if($viaje->observaciones)
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-bold">
                    <i class="bi bi-chat-left-text me-2"></i>Observaciones
                </div>
                <div class="card-body">
                    <p class="mb-0">{{ $viaje->observaciones }}</p>
                </div>
            </div>
        </div>
        @endif

        {{-- ===== CASETAS (ancho completo, al final) ===== --}}
        <div class="col-12">
            @include('viajes._casetas')
        </div>

        <div class="col-12">
            @include('viajes._combustible')
        </div>

        <div class="col-12">
            @include('viajes._reparaciones')
        </div>

    </div>

</div>
@endsection