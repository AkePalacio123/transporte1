@extends('layouts.app')

@section('title','Guías')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">
            <i class="bi bi-journal-text me-2"></i>Guías de la empresa
        </h2>
        @if(auth()->user()?->puedeEditar())
            <a href="{{ route('guias.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Nueva guía
            </a>
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- ============ FILTROS ============ --}}
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" class="row g-2">
                <div class="col-md-6">
                    <label class="form-label small mb-1">Buscar</label>
                    <input type="text" name="buscar" value="{{ $filtros['buscar'] ?? '' }}"
                           class="form-control" placeholder="Título, descripción...">
                </div>
                <div class="col-md-4">
                    <label class="form-label small mb-1">Categoría</label>
                    <select name="categoria" class="form-select">
                        <option value="">Todas</option>
                        @foreach(\App\Models\Guia::CATEGORIAS as $cat)
                            <option value="{{ $cat }}" @selected(($filtros['categoria'] ?? '') == $cat)>
                                {{ $cat }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end gap-2">
                    <button class="btn btn-primary">
                        <i class="bi bi-funnel me-1"></i>Filtrar
                    </button>
                    <a href="{{ route('guias.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x-circle"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- ============ LISTA DE GUÍAS ============ --}}
    @if($guias->count() > 0)
        <div class="row g-3">
            @foreach($guias as $guia)
                <div class="col-md-6 col-lg-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body d-flex flex-column">

                            <div class="d-flex align-items-start mb-2">
                                <i class="bi {{ $guia->es_imagen ? 'bi-file-earmark-image text-info' : 'bi-file-earmark-pdf text-danger' }} fs-2 me-2"></i>
                                <div>
                                    <span class="badge bg-secondary mb-1">{{ $guia->categoria }}</span>
                                    <h6 class="fw-bold mb-0">{{ $guia->titulo }}</h6>
                                </div>
                            </div>

                            @if($guia->descripcion)
                                <p class="text-muted small flex-grow-1">{{ $guia->descripcion }}</p>
                            @else
                                <div class="flex-grow-1"></div>
                            @endif

                            <div class="d-flex gap-2 mt-2">
                                <a href="{{ asset('storage/'.$guia->ruta) }}" target="_blank"
                                   class="btn btn-sm btn-primary flex-grow-1">
                                    <i class="bi bi-eye me-1"></i>Abrir guía
                                </a>
                                @if(auth()->user()?->puedeEditar())
                                    <form action="{{ route('guias.destroy', $guia) }}" method="POST"
                                          onsubmit="return confirm('¿Eliminar esta guía?')">
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
                </div>
            @endforeach
        </div>

        <div class="mt-3">
            {{ $guias->links() }}
        </div>
    @else
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center text-muted py-5">
                <i class="bi bi-journal-x d-block fs-1 mb-2"></i>
                No hay guías registradas.
                @if(auth()->user()?->puedeEditar())
                    <div class="mt-3">
                        <a href="{{ route('guias.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-2"></i>Subir la primera guía
                        </a>
                    </div>
                @endif
            </div>
        </div>
    @endif

</div>
@endsection