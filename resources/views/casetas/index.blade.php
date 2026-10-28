@extends('layouts.app')

@section('title','Casetas')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">
            <i class="bi bi-cone-striped me-2"></i>Casetas
        </h2>
    </div>

    <p class="text-muted">
        Consulta y exportación de todas las casetas registradas en los viajes.
        Las casetas se capturan desde la pantalla de cada viaje.
    </p>

    {{-- ============ FILTROS ============ --}}
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" class="row g-2">

                <div class="col-md-3">
                    <label class="form-label small mb-1">Buscar</label>
                    <input type="text" name="buscar" value="{{ $filtros['buscar'] ?? '' }}"
                           class="form-control" placeholder="Caseta, ticket, folio o carta porte...">
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
                    <label class="form-label small mb-1">Pago del viaje</label>
                    <select name="estatus_pago" class="form-select">
                        <option value="">Todos</option>
                        @foreach(['Pendiente','Pagado'] as $op)
                            <option value="{{ $op }}" @selected(($filtros['estatus_pago'] ?? '') == $op)>
                                {{ $op }}
                            </option>
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
                    <a href="{{ route('casetas.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x-circle me-1"></i>Limpiar
                    </a>
                    <a href="{{ route('reportes.casetas', request()->query()) }}" class="btn btn-success">
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
                    {{ $casetas->total() }} caseta(s) encontrada(s)
                </small>
                <small class="fw-bold">
                    Total filtrado: ${{ number_format($sumaTotal, 2) }}
                </small>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">

                    <thead class="table-light">
                        <tr>
                            <th width="50">#</th>
                            <th>Viaje (Folio / C.P.)</th>
                            <th>Empresa</th>
                            <th>Operador</th>
                            <th>Caseta</th>
                            <th>Fecha</th>
                            <th>Ticket</th>
                            <th class="text-end">Costo</th>
                            <th>Pago viaje</th>
                            <th class="text-center">Ver viaje</th>
                        </tr>
                    </thead>

                    <tbody>
                    @forelse($casetas as $indice => $caseta)
                        <tr>
                            <td class="text-muted">
                                {{ $casetas->firstItem() + $indice }}
                            </td>
                            <td>
                                <span class="fw-semibold">
                                    {{ $caseta->viaje->folio ?: $caseta->viaje->cataporte ?? '—' }}
                                </span>
                            </td>
                            <td>{{ $caseta->viaje->empresa->nombre ?? '—' }}</td>
                            <td>
                                <small>
                                    {{ $caseta->viaje->operador->nombre
                                        ?? $caseta->viaje->operador->nombre_completo
                                        ?? '—' }}
                                </small>
                            </td>
                            <td>{{ $caseta->nombre }}</td>
                            <td>{{ optional($caseta->fecha)->format('d/m/Y') }}</td>
                            <td><small>{{ $caseta->folio_ticket ?: '—' }}</small></td>
                            <td class="text-end">${{ number_format($caseta->costo, 2) }}</td>
                            <td>
                                @php $pagado = ($caseta->viaje->estatus_pago ?? '') === 'Pagado'; @endphp
                                <span class="badge bg-{{ $pagado ? 'success' : 'warning text-dark' }}">
                                    {{ $caseta->viaje->estatus_pago ?? '—' }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if($caseta->viaje)
                                    <a href="{{ route('viajes.show', $caseta->viaje) }}"
                                       class="btn btn-info btn-sm text-white" title="Ver viaje">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center text-muted py-4">
                                <i class="bi bi-inbox d-block fs-3 mb-2"></i>
                                No se encontraron casetas.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>

                </table>
            </div>

            {{ $casetas->links() }}

        </div>
    </div>

</div>
@endsection