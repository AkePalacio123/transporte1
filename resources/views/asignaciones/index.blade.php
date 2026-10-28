@extends('layouts.app')
@section('title','Asignaciones')
@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0"><i class="bi bi-diagram-3 me-2"></i>Asignaciones</h2>
        
        @if(auth()->user()->puedeEditar())
        <a href="{{ route('asignaciones.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>Nueva asignación
        </a>
        @endif
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">

            <form method="GET" class="row g-2 mb-3">
                <div class="col-md-4">
                    <input type="text" name="buscar" value="{{ $buscar }}" class="form-control"
                           placeholder="Buscar por operador, serie o placa...">
                </div>
                <div class="col-md-2">
                    <select name="activa" class="form-select">
                        <option value="">Todas</option>
                        <option value="1" @selected($activa === '1')>Activas</option>
                        <option value="0" @selected($activa === '0')>Inactivas</option>
                    </select>
                </div>
                <div class="col-auto">
                    <button class="btn btn-primary"><i class="bi bi-search"></i></button>
                </div>
                @if($buscar || $activa !== null)
                    <div class="col-auto">
                        <a href="{{ route('asignaciones.index') }}" class="btn btn-outline-secondary">Limpiar</a>
                    </div>
                @endif
            </form>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Operador</th>
                            <th>Tracto</th>
                            <th>Equipo</th>
                            <th>Inicio</th>
                            <th>Fin</th>
                            <th>Estado</th>
                            <th width="150">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($asignaciones as $asig)
                        <tr>
                            <td class="text-muted">
                                {{ $asignaciones->firstItem() + $loop->index }}
                            </td>
                            <td class="fw-semibold">
                                {{ $asig->operador->nombre ?? $asig->operador->nombre_completo ?? '—' }}
                            </td>
                            <td>{{ $asig->tracto->serie ?? '—' }}</td>
                            <td>{{ $asig->equipo->serie ?? '—' }}</td>
                            <td>{{ optional($asig->fecha_inicio)->format('d/m/Y') }}</td>
                            <td>{{ optional($asig->fecha_fin)->format('d/m/Y') ?: '—' }}</td>
                            <td>
                                @if($asig->activa)
                                    <span class="badge bg-success">Activa</span>
                                @else
                                    <span class="badge bg-secondary">Inactiva</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('asignaciones.show',$asig) }}"
                                       class="btn btn-info btn-sm text-white" title="Ver">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('asignaciones.edit',$asig) }}"
                                       class="btn btn-warning btn-sm text-white" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('asignaciones.destroy',$asig) }}" method="POST"
                                          onsubmit="return confirm('¿Eliminar esta asignación?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm" title="Eliminar">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="bi bi-inbox d-block fs-3 mb-2"></i>
                                No hay asignaciones registradas.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            {{ $asignaciones->links() }}

        </div>
    </div>

</div>
@endsection