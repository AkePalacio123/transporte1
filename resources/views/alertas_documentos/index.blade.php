@extends('layouts.app')

@section('title','Alertas de documentos')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">
            <i class="bi bi-file-earmark-medical me-2"></i>Alertas de documentos
        </h2>
    </div>

    <p class="text-muted">
        Documentos de tractos y equipos que ya vencieron o están próximos a vencer
        (dentro de 30 días). Sirve para evitar multas por papeles vencidos como
        seguro, verificación físico-mecánica o contaminantes.
    </p>

    {{-- ============ VENCIDOS ============ --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-danger text-white fw-bold">
            <i class="bi bi-exclamation-octagon-fill me-2"></i>
            Documentos vencidos ({{ $vencidos->count() }})
        </div>
        <div class="card-body">
            @if($vencidos->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Unidad</th>
                                <th>Tipo</th>
                                <th>Documento</th>
                                <th>Venció el</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($vencidos as $d)
                            <tr class="table-danger">
                                <td class="fw-semibold">{{ $d->unidad }}</td>
                                <td><span class="badge bg-secondary">{{ $d->tipo_unidad }}</span></td>
                                <td>{{ $d->categoria }}</td>
                                <td>
                                    <span class="text-danger fw-semibold">
                                        <i class="bi bi-exclamation-triangle-fill me-1"></i>
                                        {{ $d->vence->format('d/m/Y') }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ asset('storage/'.$d->ruta) }}" target="_blank"
                                       class="btn btn-sm btn-outline-primary" title="Ver documento">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route($d->ruta_show, $d->unidad_id) }}"
                                       class="btn btn-sm btn-outline-secondary" title="Ver unidad">
                                        <i class="bi bi-truck"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center text-muted py-3">
                    <i class="bi bi-check-circle d-block fs-3 mb-2 text-success"></i>
                    No hay documentos vencidos.
                </div>
            @endif
        </div>
    </div>

    {{-- ============ POR VENCER ============ --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-warning fw-bold">
            <i class="bi bi-clock-history me-2"></i>
            Próximos a vencer ({{ $porVencer->count() }})
        </div>
        <div class="card-body">
            @if($porVencer->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Unidad</th>
                                <th>Tipo</th>
                                <th>Documento</th>
                                <th>Vence el</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($porVencer as $d)
                            <tr class="table-warning">
                                <td class="fw-semibold">{{ $d->unidad }}</td>
                                <td><span class="badge bg-secondary">{{ $d->tipo_unidad }}</span></td>
                                <td>{{ $d->categoria }}</td>
                                <td>
                                    <span class="text-warning-emphasis fw-semibold">
                                        <i class="bi bi-clock me-1"></i>
                                        {{ $d->vence->format('d/m/Y') }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ asset('storage/'.$d->ruta) }}" target="_blank"
                                       class="btn btn-sm btn-outline-primary" title="Ver documento">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route($d->ruta_show, $d->unidad_id) }}"
                                       class="btn btn-sm btn-outline-secondary" title="Ver unidad">
                                        <i class="bi bi-truck"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center text-muted py-3">
                    <i class="bi bi-check-circle d-block fs-3 mb-2 text-success"></i>
                    No hay documentos próximos a vencer.
                </div>
            @endif
        </div>
    </div>

</div>
@endsection