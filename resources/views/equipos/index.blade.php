@extends('layouts.app')
@section('title','Equipos')
@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0"><i class="bi bi-box-seam me-2"></i>Equipos</h2>
        @if(auth()->user()?->puedeEditar())
        <a href="{{ route('equipos.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>Nuevo equipo
        </a>
        @endif
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">

            <form method="GET" class="row g-2 mb-3">
                <div class="col-md-4">
                    <input type="text" name="buscar" value="{{ $buscar }}" class="form-control"
                           placeholder="Buscar por serie, placa, marca o modelo...">
                </div>
                <div class="col-md-2">
                    <select name="estatus" class="form-select">
                        <option value="">Todos los estatus</option>
                        @foreach(['Activo','Inactivo','Mantenimiento','Baja'] as $op)
                            <option value="{{ $op }}" @selected($estatus == $op)>{{ $op }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <button class="btn btn-primary"><i class="bi bi-search"></i></button>
                </div>
                @if($buscar || $estatus)
                    <div class="col-auto">
                        <a href="{{ route('equipos.index') }}" class="btn btn-outline-secondary">Limpiar</a>
                    </div>
                @endif
            </form>

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

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Serie (VIN)</th>
                            <th>Placa</th>
                            <th>Marca</th>
                            <th>Modelo</th>
                            <th>Año</th>
                            <th class="text-center">Viajes</th>
                            <th>Estatus</th>
                            <th width="180">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($equipos as $item)
                        <tr>
                            <td class="text-muted">
                                {{ $equipos->firstItem() + $loop->index }}
                            </td>
                            <td class="fw-semibold">{{ $item->serie }}</td>
                            <td>{{ $item->placa }}</td>
                            <td>{{ $item->marca }}</td>
                            <td>{{ $item->modelo }}</td>
                            <td>{{ $item->anio }}</td>
                            <td class="text-center">
                                <span class="badge bg-secondary">{{ $item->viajes_count }}</span>
                            </td>
                            <td>
                                @php
                                    $colores = [
                                        'Activo'        => 'success',
                                        'Inactivo'      => 'secondary',
                                        'Mantenimiento' => 'warning text-dark',
                                        'Baja'          => 'danger',
                                    ];
                                @endphp
                                <span class="badge bg-{{ $colores[$item->estatus] ?? 'secondary' }}">{{ $item->estatus }}</span>
                            </td>
                            <td>
                                <div class="d-flex flex-wrap gap-1">
                                    <a href="{{ route('equipos.show',$item) }}" class="btn btn-info btn-sm text-white" title="Ver">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @if(auth()->user()?->puedeEditar())
                                        <a href="{{ route('equipos.edit',$item) }}" class="btn btn-warning btn-sm text-white" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        @if($item->viajes_count > 0)
                                            <button class="btn btn-danger btn-sm" disabled title="Tiene viajes registrados">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        @else
                                            <form action="{{ route('equipos.destroy',$item) }}" method="POST"
                                                  onsubmit="return confirm('¿Eliminar {{ $item->serie }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-danger btn-sm" title="Eliminar">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">
                                <i class="bi bi-inbox d-block fs-3 mb-2"></i>No hay equipos registrados.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            {{ $equipos->links() }}

        </div>
    </div>
</div>
@endsection