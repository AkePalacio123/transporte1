@extends('layouts.app')
@section('title','Detalle del tracto')
@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0"><i class="bi bi-truck-front me-2"></i>Tracto {{ $tracto->serie }}</h2>
        <div class="d-flex gap-2">
            @if(auth()->user()?->puedeEditar())
                <a href="{{ route('tractos.edit',$tracto) }}" class="btn btn-warning text-white">
                    <i class="bi bi-pencil me-1"></i>Editar
                </a>
            @endif
            <a href="{{ route('tractos.index') }}" class="btn btn-outline-secondary">
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

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-triangle me-2"></i>
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row g-3">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white fw-bold"><i class="bi bi-info-circle me-2"></i>Datos generales</div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-5">Serie (VIN)</dt><dd class="col-7">{{ $tracto->serie }}</dd>
                        <dt class="col-5">Placa</dt><dd class="col-7">{{ $tracto->placa }}</dd>
                        <dt class="col-5">Marca</dt><dd class="col-7">{{ $tracto->marca }}</dd>
                        <dt class="col-5">Modelo</dt><dd class="col-7">{{ $tracto->modelo }}</dd>
                        <dt class="col-5">Año</dt><dd class="col-7">{{ $tracto->anio }}</dd>
                        <dt class="col-5">No. tarjeta</dt><dd class="col-7">{{ $tracto->tarjeta_circulacion ?: '—' }}</dd>
                        <dt class="col-5">Estatus</dt><dd class="col-7">{{ $tracto->estatus }}</dd>
                        
                    </dl>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white fw-bold"><i class="bi bi-graph-up me-2"></i>Actividad</div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="text-muted small text-uppercase">Viajes realizados</div>
                        <div class="fs-2 fw-bold">{{ $tracto->viajes_count }}</div>
                    </div>
                    @if($tracto->observaciones)
                        <div class="text-muted small text-uppercase">Observaciones</div>
                        <p class="mb-0">{{ $tracto->observaciones }}</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- ============ DOCUMENTOS ============ --}}
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-bold">
                    <i class="bi bi-folder2-open me-2"></i>Documentos del tracto
                </div>
                <div class="card-body">

                    @if(auth()->user()?->puedeEditar())
                        <form action="{{ route('tractos.documentos.store', $tracto) }}"
                              method="POST" enctype="multipart/form-data" class="row g-2 mb-4">
                            @csrf
                            <div class="col-md-3">
                                <label class="form-label small mb-1">Categoría <span class="text-danger">*</span></label>
                                <select name="categoria" class="form-select" required>
                                    <option value="">-- Selecciona --</option>
                                    @foreach(\App\Models\DocumentoTracto::CATEGORIAS as $cat)
                                        <option value="{{ $cat }}">{{ $cat }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small mb-1">Archivo <span class="text-danger">*</span></label>
                                <input type="file" name="archivo" class="form-control"
                                       accept=".pdf,.jpg,.jpeg,.png,.webp" required>
                                <div class="form-text">PDF o imagen, máx. 10 MB.</div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small mb-1">Vence (opcional)</label>
                                <input type="date" name="fecha_vencimiento" class="form-control">
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bi bi-upload me-1"></i>Subir documento
                                </button>
                            </div>
                            <div class="col-12">
                                <input type="text" name="observaciones" maxlength="500"
                                       class="form-control form-control-sm"
                                       placeholder="Observaciones (opcional)">
                            </div>
                        </form>
                        <hr>
                    @endif

                    @forelse($tracto->documentos as $doc)
                        @php
                            $filaClase = $doc->esta_vencido ? 'border-danger'
                                      : ($doc->esta_por_vencer ? 'border-warning' : '');
                        @endphp
                        <div class="card mb-2 {{ $filaClase }}" @if($filaClase) style="border-width:2px" @endif>
                            <div class="card-body py-2 d-flex align-items-center justify-content-between flex-wrap">
                                <div class="d-flex align-items-center">
                                    <i class="bi {{ $doc->es_imagen ? 'bi-file-earmark-image text-info' : 'bi-file-earmark-pdf text-danger' }} fs-3 me-3"></i>
                                    <div>
                                        <span class="fw-semibold">{{ $doc->categoria }}</span>
                                        <small class="d-block text-muted">{{ $doc->nombre_archivo }}</small>
                                        @if($doc->observaciones)
                                            <small class="d-block text-muted fst-italic">{{ $doc->observaciones }}</small>
                                        @endif
                                    </div>
                                </div>
                                <div class="text-end">
                                    @if($doc->fecha_vencimiento)
                                        <div class="small mb-1">
                                            @if($doc->esta_vencido)
                                                <span class="badge bg-danger">
                                                    <i class="bi bi-exclamation-triangle-fill me-1"></i>
                                                    Venció {{ $doc->fecha_vencimiento->format('d/m/Y') }}
                                                </span>
                                            @elseif($doc->esta_por_vencer)
                                                <span class="badge bg-warning text-dark">
                                                    <i class="bi bi-clock me-1"></i>
                                                    Vence {{ $doc->fecha_vencimiento->format('d/m/Y') }}
                                                </span>
                                            @else
                                                <span class="text-muted">
                                                    Vence {{ $doc->fecha_vencimiento->format('d/m/Y') }}
                                                </span>
                                            @endif
                                        </div>
                                    @endif
                                    <a href="{{ asset('storage/'.$doc->ruta) }}" target="_blank"
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye me-1"></i>Ver
                                    </a>
                                    @if(auth()->user()?->puedeEditar())
                                        <form action="{{ route('tractos.documentos.destroy', $doc) }}"
                                              method="POST" class="d-inline"
                                              onsubmit="return confirm('¿Eliminar este documento?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-4">
                            <i class="bi bi-inbox d-block fs-3 mb-2"></i>
                            Este tracto no tiene documentos cargados.
                        </div>
                    @endforelse

                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-bold"><i class="bi bi-clock-history me-2"></i>Últimos viajes</div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Folio / C.P.</th><th>Empresa</th><th>Fecha</th>
                                    <th>Ruta</th><th>Operador</th><th class="text-end">Monto</th>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse($ultimosViajes as $viaje)
                                <tr>
                                    <td>
                                        <a href="{{ route('viajes.show',$viaje) }}" class="text-decoration-none">
                                            {{ $viaje->folio ?: $viaje->cataporte }}
                                        </a>
                                    </td>
                                    <td>{{ $viaje->empresa->nombre ?? '—' }}</td>
                                    <td>{{ optional($viaje->fecha_viaje)->format('d/m/Y') }}</td>
                                    <td><small>{{ $viaje->origen }} → {{ $viaje->destino }}</small></td>
                                    <td><small>{{ $viaje->operador->nombre ?? '—' }}</small></td>
                                    <td class="text-end">${{ number_format($viaje->pago, 2) }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="text-center text-muted py-3">Sin viajes registrados.</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection