@extends('layouts.app')
@section('title','Detalle del mantenimiento')
@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">
            <i class="bi bi-wrench-adjustable me-2"></i>
            Mantenimiento #{{ $mantenimiento->id }}
            <small class="text-muted fs-6">— {{ $mantenimiento->unidad_etiqueta }}</small>
        </h2>
        <div class="d-flex gap-2">
            <a href="{{ route('mantenimientos.edit',$mantenimiento) }}" class="btn btn-warning text-white">
                <i class="bi bi-pencil me-1"></i>Editar
            </a>
            <a href="{{ route('mantenimientos.index') }}" class="btn btn-outline-secondary">
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
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <div class="row g-3">

        {{-- DATOS GENERALES --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white fw-bold">
                    <i class="bi bi-info-circle me-2"></i>Datos del servicio
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-5">Unidad</dt>
                        <dd class="col-7">
                            {{ $mantenimiento->tipo_unidad }} {{ $mantenimiento->unidad->serie ?? '—' }}
                            <small class="d-block text-muted">
                                {{ $mantenimiento->unidad->placa ?? '' }}
                            </small>
                        </dd>

                        <dt class="col-5">Tipo</dt>
                        <dd class="col-7">
                            <span class="badge bg-{{ $mantenimiento->tipo == 'Correctivo' ? 'danger' : 'info' }}">
                                {{ $mantenimiento->tipo }}
                            </span>
                        </dd>

                        <dt class="col-5">Estatus</dt>
                        <dd class="col-7">{{ $mantenimiento->estatus }}</dd>

                        <dt class="col-5">Entrada</dt>
                        <dd class="col-7">{{ optional($mantenimiento->fecha_entrada)->format('d/m/Y') }}</dd>

                        <dt class="col-5">Salida</dt>
                        <dd class="col-7">
                            {{ optional($mantenimiento->fecha_salida)->format('d/m/Y') ?: '— (en taller)' }}
                            @if($mantenimiento->dias_en_taller !== null)
                                <small class="text-muted">({{ $mantenimiento->dias_en_taller }} día/s)</small>
                            @endif
                        </dd>

                        <dt class="col-5">Kilometraje</dt>
                        <dd class="col-7">
                            {{ $mantenimiento->kilometraje ? number_format($mantenimiento->kilometraje) . ' km' : '—' }}
                        </dd>

                        <dt class="col-5">Taller</dt>
                        <dd class="col-7">{{ $mantenimiento->taller ?: '—' }}</dd>

                        <dt class="col-5">Factura</dt>
                        <dd class="col-7">
                            {{ $mantenimiento->folio_factura ?: '—' }}
                            @if($mantenimiento->factura_pdf)
                                <a href="{{ asset('storage/'.$mantenimiento->factura_pdf) }}" target="_blank"
                                   class="btn btn-sm btn-outline-danger ms-1">
                                    <i class="bi bi-file-earmark-pdf"></i>
                                </a>
                            @endif
                        </dd>
                    </dl>
                </div>
            </div>
        </div>

        {{-- COSTOS --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white fw-bold">
                    <i class="bi bi-cash-coin me-2"></i>Costos
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="text-muted small text-uppercase">Costo total</div>
                        <span class="fs-2 fw-bold">${{ number_format($mantenimiento->costo_total, 2) }}</span>
                    </div>
                    <dl class="row mb-3">
                        <dt class="col-6">Mano de obra</dt>
                        <dd class="col-6 text-end">${{ number_format($mantenimiento->costo_mano_obra, 2) }}</dd>

                        <dt class="col-6">Piezas ({{ $mantenimiento->piezas->count() }})</dt>
                        <dd class="col-6 text-end">${{ number_format($mantenimiento->total_piezas, 2) }}</dd>
                    </dl>

                    <div class="text-muted small text-uppercase">Descripción del servicio</div>
                    <p class="mb-0">{{ $mantenimiento->descripcion }}</p>

                    @if($mantenimiento->observaciones)
                        <div class="text-muted small text-uppercase mt-3">Observaciones</div>
                        <p class="mb-0">{{ $mantenimiento->observaciones }}</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- PIEZAS --}}
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <span class="fw-bold"><i class="bi bi-gear-wide-connected me-2"></i>Piezas colocadas</span>
                    <span class="badge bg-dark fs-6">
                        Total: ${{ number_format($mantenimiento->total_piezas, 2) }}
                    </span>
                </div>
                <div class="card-body">

                    <div class="table-responsive mb-3">
                        <table class="table table-sm table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Pieza</th>
                                    <th>No. parte</th>
                                    <th>Proveedor</th>
                                    <th class="text-end">Cant.</th>
                                    <th class="text-end">P. unitario</th>
                                    <th class="text-end">Subtotal</th>
                                    <th width="60"></th>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse($mantenimiento->piezas as $pieza)
                                <tr>
                                    <td>{{ $pieza->nombre }}</td>
                                    <td>{{ $pieza->numero_parte ?: '—' }}</td>
                                    <td>{{ $pieza->proveedor ?: '—' }}</td>
                                    <td class="text-end">{{ rtrim(rtrim(number_format($pieza->cantidad, 2), '0'), '.') }}</td>
                                    <td class="text-end">${{ number_format($pieza->precio_unitario, 2) }}</td>
                                    <td class="text-end fw-semibold">${{ number_format($pieza->subtotal, 2) }}</td>
                                    <td>
                                        <form action="{{ route('piezas.destroy', $pieza) }}" method="POST"
                                              onsubmit="return confirm('¿Eliminar esta pieza?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm" title="Eliminar">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-3">
                                        Aún no se han registrado piezas para este mantenimiento.
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Formulario para agregar pieza --}}
                    <form action="{{ route('mantenimientos.piezas.store', $mantenimiento) }}" method="POST" class="row g-2">
                        @csrf

                        <div class="col-md-3">
                            <input type="text" name="nombre" maxlength="150" required
                                   value="{{ old('nombre') }}"
                                   class="form-control form-control-sm" placeholder="Nombre de la pieza">
                        </div>

                        <div class="col-md-2">
                            <input type="text" name="numero_parte" maxlength="60"
                                   value="{{ old('numero_parte') }}"
                                   class="form-control form-control-sm" placeholder="No. parte">
                        </div>

                        <div class="col-md-2">
                            <input type="text" name="proveedor" maxlength="120"
                                   value="{{ old('proveedor') }}"
                                   class="form-control form-control-sm" placeholder="Proveedor">
                        </div>

                        <div class="col-md-2">
                            <input type="number" name="cantidad" step="0.01" min="0.01" required
                                   value="{{ old('cantidad', 1) }}"
                                   class="form-control form-control-sm" placeholder="Cantidad">
                        </div>

                        <div class="col-md-2">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text">$</span>
                                <input type="number" name="precio_unitario" step="0.01" min="0" required
                                       value="{{ old('precio_unitario') }}"
                                       class="form-control" placeholder="P. unitario">
                            </div>
                        </div>

                        <div class="col-md-1">
                            <button class="btn btn-primary btn-sm w-100" title="Agregar pieza">
                                <i class="bi bi-plus-lg"></i>
                            </button>
                        </div>

                    </form>

                    <small class="text-muted d-block mt-2">
                        El subtotal se calcula solo: cantidad × precio unitario.
                    </small>

                </div>
            </div>
        </div>

    </div>

</div>
@endsection