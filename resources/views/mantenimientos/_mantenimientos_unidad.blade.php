{{-- Historial de mantenimientos: se incluye en tractos/show y equipos/show --}}
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <span class="fw-bold">
            <i class="bi bi-wrench-adjustable me-2"></i>Historial de mantenimientos
        </span>
        <span class="badge bg-dark fs-6">
            Total: ${{ number_format($mantenimientos->sum(fn($m) => $m->costo_total), 2) }}
        </span>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-sm table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Entrada</th>
                        <th>Salida</th>
                        <th>Tipo</th>
                        <th>Taller</th>
                        <th>Descripción</th>
                        <th class="text-end">Km</th>
                        <th class="text-end">Costo</th>
                        <th>Estatus</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($mantenimientos as $mto)
                    <tr>
                        <td>
                            <a href="{{ route('mantenimientos.show',$mto) }}" class="text-decoration-none">
                                {{ optional($mto->fecha_entrada)->format('d/m/Y') }}
                            </a>
                        </td>
                        <td>{{ optional($mto->fecha_salida)->format('d/m/Y') ?: '—' }}</td>
                        <td>
                            <span class="badge bg-{{ $mto->tipo == 'Correctivo' ? 'danger' : 'info' }}">
                                {{ $mto->tipo }}
                            </span>
                        </td>
                        <td><small>{{ $mto->taller ?: '—' }}</small></td>
                        <td><small>{{ Str::limit($mto->descripcion, 45) }}</small></td>
                        <td class="text-end">{{ $mto->kilometraje ? number_format($mto->kilometraje) : '—' }}</td>
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
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-3">
                            Sin mantenimientos registrados para esta unidad.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>