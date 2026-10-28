@extends('layouts.app')

@section('title','Viajes')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">
            <i class="bi bi-signpost-split me-2"></i>Viajes
        </h2>
        @if(auth()->user()?->puedeEditar())
            <a href="{{ route('viajes.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Nuevo viaje
            </a>
        @endif
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

    {{-- ============ FILTROS ============ --}}
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" class="row g-2">

                <div class="col-md-3">
                    <label class="form-label small mb-1">Buscar</label>
                    <input type="text" name="buscar" value="{{ $filtros['buscar'] ?? '' }}"
                           class="form-control" placeholder="Folio, carta porte, origen, destino...">
                </div>

                <div class="col-md-2">
                    <label class="form-label small mb-1">Empresa</label>
                    <select name="empresa_id" class="form-select">
                        <option value="">Todas</option>
                        @foreach($empresas as $empresa)
                            <option value="{{ $empresa->id }}"
                                @selected(($filtros['empresa_id'] ?? '') == $empresa->id)>
                                {{ $empresa->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label small mb-1">Operador</label>
                    <select name="operador_id" class="form-select">
                        <option value="">Todos</option>
                        @foreach($operadores as $operador)
                            <option value="{{ $operador->id }}"
                                @selected(($filtros['operador_id'] ?? '') == $operador->id)>
                                {{ $operador->nombre ?? $operador->nombre_completo ?? 'Operador #'.$operador->id }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label small mb-1">Estatus</label>
                    <select name="estatus" class="form-select">
                        <option value="">Todos</option>
                        @foreach(['Pendiente','En ruta','Finalizado','Cancelado'] as $op)
                            <option value="{{ $op }}" @selected(($filtros['estatus'] ?? '') == $op)>
                                {{ $op }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label small mb-1">Pago</label>
                    <select name="estatus_pago" class="form-select">
                        <option value="">Todos</option>
                        @foreach(['Pendiente','Pagado'] as $op)
                            <option value="{{ $op }}" @selected(($filtros['estatus_pago'] ?? '') == $op)>
                                {{ $op }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-1">
                    <label class="form-label small mb-1">Tipo</label>
                    <select name="tipo_viaje" class="form-select">
                        <option value="">Todos</option>
                        @foreach(['Normal','Retorno'] as $op)
                            <option value="{{ $op }}" @selected(($filtros['tipo_viaje'] ?? '') == $op)>
                                {{ $op }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label small mb-1">Alerta de pago</label>
                    <select name="alerta" class="form-select">
                        <option value="">Todas</option>
                        <option value="vencido" @selected(($filtros['alerta'] ?? '') == 'vencido')>
                            Solo vencidos
                        </option>
                        <option value="proximo" @selected(($filtros['alerta'] ?? '') == 'proximo')>
                            Próximos a vencer
                        </option>
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
                    <a href="{{ route('viajes.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x-circle me-1"></i>Limpiar
                    </a>
                    <a href="{{ route('reportes.viajes', request()->query()) }}" class="btn btn-success">
                        <i class="bi bi-file-earmark-spreadsheet me-1"></i>Exportar CSV
                    </a>
                </div>

            </form>
        </div>
    </div>

    {{-- ============ TABLA ============ --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center mb-2">
                <small class="text-muted">
                    {{ $totales['cantidad'] }} viaje(s) encontrado(s)
                </small>

                {{-- Leyenda de colores --}}
                <small class="text-muted">
                    <span class="badge bg-danger me-1">&nbsp;</span> Vencido
                    <span class="badge bg-warning ms-2 me-1">&nbsp;</span> Próximo a vencer
                </small>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">

                    <thead class="table-light">
                        <tr>
                            <th width="50">#</th>
                            <th>Folio / C. Porte</th>
                            <th>Empresa</th>
                            <th>Fecha</th>
                            <th>Ruta</th>
                            <th>Operador</th>
                            <th>Tracto</th>
                            <th class="text-end">Monto</th>
                            <th>Estatus</th>
                            <th>Pago</th>
                            <th>Límite de pago</th>
                            <th class="text-center">PDF</th>
                            <th width="150">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                    @forelse($viajes as $indice => $viaje)
                        @php
                            // Color de fila según el estado de la alerta de pago
                            $filaClase = match($viaje->estado_alerta) {
                                'vencido' => 'table-danger',
                                'proximo' => 'table-warning',
                                default   => '',
                            };
                        @endphp
                        <tr class="{{ $filaClase }}">
                            <td class="text-muted">
                                {{ $viajes->firstItem() + $indice }}
                            </td>
                            <td>
                                <span class="fw-semibold">{{ $viaje->folio ?: '—' }}</span>
                                <small class="d-block text-muted">{{ $viaje->cataporte }}</small>
                            </td>

                            <td>{{ $viaje->empresa->nombre ?? '—' }}</td>

                            <td>{{ optional($viaje->fecha_viaje)->format('d/m/Y') }}</td>

                            <td>
                                <small>{{ $viaje->origen }}</small>
                                <i class="bi bi-arrow-right mx-1 text-muted"></i>
                                <small>{{ $viaje->destino }}</small>
                            </td>

                            <td>
                                <small>
                                    {{ $viaje->operador->nombre
                                        ?? $viaje->operador->nombre_completo
                                        ?? '—' }}
                                </small>
                            </td>

                            <td>
                                <small>{{ $viaje->tracto->serie ?? '—' }}</small>
                            </td>

                            <td class="text-end">${{ number_format($viaje->pago, 2) }}</td>

                            <td>
                                @php
                                    $colores = [
                                        'Pendiente'  => 'secondary',
                                        'En ruta'    => 'info',
                                        'Finalizado' => 'success',
                                        'Cancelado'  => 'danger',
                                    ];
                                @endphp
                                <span class="badge bg-{{ $colores[$viaje->estatus] ?? 'secondary' }}">
                                    {{ $viaje->estatus }}
                                </span>
                            </td>

                            <td>
                                <span class="badge bg-{{ $viaje->estatus_pago == 'Pagado' ? 'success' : 'warning text-dark' }}">
                                    {{ $viaje->estatus_pago }}
                                </span>
                            </td>

                            {{-- Columna de límite de pago con aviso de días --}}
                            <td>
                                @if($viaje->fecha_limite_pago)
                                    <small class="d-block">
                                        {{ $viaje->fecha_limite_pago->format('d/m/Y') }}
                                    </small>
                                    @if($viaje->estado_alerta === 'vencido')
                                        <small class="text-danger fw-semibold">
                                            <i class="bi bi-exclamation-triangle-fill"></i>
                                            Venció hace {{ abs($viaje->dias_para_vencer) }}
                                            {{ abs($viaje->dias_para_vencer) == 1 ? 'día' : 'días' }}
                                        </small>
                                    @elseif($viaje->estado_alerta === 'proximo')
                                        <small class="text-warning-emphasis fw-semibold">
                                            <i class="bi bi-clock"></i>
                                            @if($viaje->dias_para_vencer == 0)
                                                Vence hoy
                                            @else
                                                Faltan {{ $viaje->dias_para_vencer }}
                                                {{ $viaje->dias_para_vencer == 1 ? 'día' : 'días' }}
                                            @endif
                                        </small>
                                    @endif
                                @else
                                    <small class="text-muted">—</small>
                                @endif
                            </td>

                            <td class="text-center">
                                @if($viaje->archivo_bitacora)
                                    <a href="{{ asset('storage/'.$viaje->archivo_bitacora) }}"
                                       target="_blank" class="text-danger" title="Ver bitácora">
                                        <i class="bi bi-file-earmark-pdf fs-5"></i>
                                    </a>
                                @else
                                    <i class="bi bi-dash text-muted"></i>
                                @endif
                            </td>

                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('viajes.show',$viaje) }}"
                                       class="btn btn-info btn-sm text-white" title="Ver">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @if(auth()->user()?->puedeEditar())
                                        <a href="{{ route('viajes.edit',$viaje) }}"
                                           class="btn btn-warning btn-sm text-white" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('viajes.destroy',$viaje) }}" method="POST"
                                              onsubmit="return confirm('¿Eliminar este viaje? Esta acción no se puede deshacer.')">
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
                                No se encontraron viajes.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>

                </table>
            </div>

            {{ $viajes->links() }}

        </div>
    </div>

</div>
@endsection