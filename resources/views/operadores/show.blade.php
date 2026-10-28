@extends('layouts.app')
@section('title', 'Detalle del Operador')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">
                Detalle del Operador
            </h2>
            <p class="text-muted mb-0">
                Información completa del operador
            </p>
        </div>
        <a href="{{ route('operadores.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i>
            Regresar
        </a>
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

    {{-- ============ DATOS DEL OPERADOR ============ --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                {{ $operador->nombre }}
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="fw-bold text-muted">Nombre</label>
                    <p class="fs-5">{{ $operador->nombre }}</p>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="fw-bold text-muted">Teléfono</label>
                    <p class="fs-5">{{ $operador->telefono }}</p>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="fw-bold text-muted">Licencia</label>
                    <p class="fs-5">{{ $operador->licencia }}</p>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="fw-bold text-muted">Vencimiento</label>
                    <p class="fs-5">
                        {{ \Carbon\Carbon::parse($operador->vencimiento_licencia)->format('d/m/Y') }}
                    </p>
                </div>
                <div class="col-md-6">
                    <label class="fw-bold text-muted">Estatus</label>
                    <p>
                        @if($operador->estatus=='Activo')
                            <span class="badge bg-success fs-6">Activo</span>
                        @elseif($operador->estatus=='Suspendido')
                            <span class="badge bg-warning text-dark fs-6">Suspendido</span>
                        @else
                            <span class="badge bg-danger fs-6">Inactivo</span>
                        @endif
                    </p>
                </div>
                <div class="col-md-6">
                    <label class="fw-bold text-muted">Registrado</label>
                    <p class="fs-5">{{ $operador->created_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>
        <div class="card-footer bg-white text-end">
            <a href="{{ route('operadores.edit',$operador) }}" class="btn btn-warning">
                <i class="bi bi-pencil-square me-2"></i>
                Editar
            </a>
        </div>
    </div>

    {{-- ============ DOCUMENTOS ============ --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white fw-bold">
            <i class="bi bi-folder2-open me-2"></i>Documentos del operador
        </div>
        <div class="card-body">

            {{-- Formulario para subir (solo si puede editar) --}}
            @if(auth()->user()?->puedeEditar())
                <form action="{{ route('operadores.documentos.store', $operador) }}"
                      method="POST" enctype="multipart/form-data" class="row g-2 mb-4">
                    @csrf

                    <div class="col-md-3">
                        <label class="form-label small mb-1">Categoría <span class="text-danger">*</span></label>
                        <select name="categoria" class="form-select" required>
                            <option value="">-- Selecciona --</option>
                            @foreach(\App\Models\DocumentoOperador::CATEGORIAS as $cat)
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

            {{-- Lista de documentos --}}
            @forelse($operador->documentos as $doc)
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
                            {{-- Estado de vencimiento --}}
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

                            {{-- Botones --}}
                            <a href="{{ asset('storage/'.$doc->ruta) }}" target="_blank"
                               class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye me-1"></i>Ver
                            </a>
                            @if(auth()->user()?->puedeEditar())
                                <form action="{{ route('operadores.documentos.destroy', $doc) }}"
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
                    Este operador no tiene documentos cargados.
                </div>
            @endforelse

        </div>
    </div>

</div>
@endsection