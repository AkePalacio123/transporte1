@extends('layouts.app')
@section('title','Mantenimientos')
@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0"><i class="bi bi-wrench-adjustable me-2"></i>Mantenimientos</h2>
        @if(auth()->user()?->puedeEditar())
            <a href="{{ route('mantenimientos.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Nuevo mantenimiento
            </a>
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- FILTROS --}}
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" class="row g-2">

                <div class="col-md-3">
                    <label class="form-label small mb-1">Buscar</label>
                    <input type="text" name="buscar" value="{{ $filtros['buscar'] ?? '' }}"
                           class="form-control" placeholder="Descripción, taller, serie, placa...">
                </div>

                <div class="col-md-3">
                    <label class="form-label small mb-1">Unidad</label>
                    <select name="unidad" class="form-select">
                        <option value="">Todas</option>
                        @php $grupoActual = null; @endphp
                        @foreach($unidades as $u)
                            @if($u['grupo'] !== $grupoActual)
                                @if($grupoActual !== null)</optgroup>@endif
                                <optgroup label="{{ $u['grupo'] }}">
                                @php $grupoActual = $u['grupo']; @endphp
                            @endif
                            <option value="{{ $u['valor'] }}" @selected(($filtros['unidad'] ?? '') == $u['valor'])>
                                {{ $u['texto'] }}
                            </option>
                        @endforeach
                        @if($grupoActual !== null)</optgroup>@endif
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label small mb-1">Tipo</label>
                    <select name="tipo" class="form-select">
                        <option value="">Todos</option>
                        @foreach(['Preventivo','Correctivo'] as $op)
                            <option value="{{ $op }}" @selected(($filtros['tipo'] ?? '') == $op)>{{ $op }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label small mb-1">Estatus</label>
                    <select name="estatus" class="form-select">
                        <option value="">Todos</option>
                        @foreach(['En taller','Finalizado','Cancelado'] as $op)
                            <option value="{{ $op }}" @selected(($filtros['estatus'] ?? '') == $op)>{{ $op }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label small mb-1">Desde</label>
                    <input type="date" name="desde" value="{{ $filtros['desde'] ?? '' }}" class="form-control">
                </div>

                <div class="col-md-2">
                    <label class="form-label small mb-1">Hasta</label>
                    <input type="date" name="hasta" value="{{ $filtros['hasta'] ?? '' }}" class="form-control">
                </div>

                <div class="col-12 d-flex gap-2 mt-2">
                    <button class="btn btn-primary">
                        <i class="bi bi-funnel me-1"></i>Filtrar
                    </button>
                    <a href="{{ route('mantenimientos.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x-circle me-1"></i>Limpiar
                    </a>
                    <a href="{{ route('reportes.mantenimientos', request()->query()) }}" class="btn btn-success">
                        <i class="bi bi-file-earmark-spreadsheet me-1"></i>Exportar CSV
                    </a>
                </div>

            </form>
        </div>
    </div>

    {{-- TABLA --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body">

            <small class="text-muted d-block mb-2">
                {{ $mantenimientos->total() }} registro(s)
            </small>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Unidad</th>
                            <th>Tipo</th>
                            <th>Entrada</th>
                            <th>Salida</th>
                            <th>Taller</th>
                            <th>Descripción</th>
                            <th class="text-end">Mano obra</th>
                            <th class="text-end">Piezas</th>
                            <th class="text-end">Total</th>
                            <th>Estatus</th>
                            <th class="text-center">PDF</th>
                            <th width="150">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($mantenimientos as $mto)
                        <tr>
                            <td class="text-muted">
                                {{ $mantenimientos->firstItem() + $loop->index }}
                            </td>
                            <td>
                                <span class="fw-semibold">{{ $mto->unidad->serie ?? '—' }}</span>
                                <small class="d-block text-muted">{{ $mto->tipo_unidad }}</small>
                            </td>
                            <td>
                                <span class="badge bg-{{ $mto->tipo == 'Correctivo' ? 'danger' : 'info' }}">
                                    {{ $mto->tipo }}
                                </span>
                            </td>
                            <td>{{ optional($mto->fecha_entrada)->format('d/m/Y') }}</td>
                            <td>{{ optional($mto->fecha_salida)->format('d/m/Y') ?: '—' }}</td>
                            <td><small>{{ $mto->taller ?: '—' }}</small></td>
                            <td><small>{{ Str::limit($mto->descripcion, 40) }}</small></td>
                            <td class="text-end">${{ number_format($mto->costo_mano_obra, 2) }}</td>
                            <td class="text-end">${{ number_format($mto->total_piezas, 2) }}</td>
                            <td class="text-end fw-semibold">${{ number_format($mto->costo_total, 2) }}</td>
                            <td>
                                @php
                                    $colores = [
                                        'En taller'  => 'warning text-dark',
                                        'Finalizado' => 'success',
                                        'Cancelado'  => 'secondary',
                                    ];
                                @endphp
                                <span class="badge bg-{{ $colores[$mto->estatus] ?? 'secondary' }}">
                                    {{ $mto->estatus }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if($mto->factura_pdf)
                                    <a href="{{ asset('storage/'.$mto->factura_pdf) }}" target="_blank"
                                       class="text-danger" title="Ver factura">
                                        <i class="bi bi-file-earmark-pdf fs-5"></i>
                                    </a>
                                @else
                                    <i class="bi bi-dash text-muted"></i>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('mantenimientos.show',$mto) }}"
                                       class="btn btn-info btn-sm text-white" title="Ver">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @if(auth()->user()?->puedeEditar())
                                        <a href="{{ route('mantenimientos.edit',$mto) }}"
                                           class="btn btn-warning btn-sm text-white" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('mantenimientos.destroy',$mto) }}" method="POST"
                                              onsubmit="return confirm('¿Eliminar este mantenimiento y sus piezas?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm" title="Eliminar">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="13" class="text-center text-muted py-4">
                                <i class="bi bi-inbox d-block fs-3 mb-2"></i>
                                No se encontraron mantenimientos.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            {{ $mantenimientos->links() }}

        </div>
    </div>

</div>
@endsection