{{-- Bloque de casetas: se incluye dentro de viajes/show.blade.php --}}
<div class="card border-0 shadow-sm">

    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <span class="fw-bold">
            <i class="bi bi-cone-striped me-2"></i>Casetas
        </span>
        <span class="badge bg-dark fs-6">
            Total: ${{ number_format($viaje->casetas->sum('costo'), 2) }}
        </span>
    </div>

    <div class="card-body">

        {{-- Tabla de casetas registradas --}}
        <div class="table-responsive mb-3">
            <table class="table table-sm table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Caseta</th>
                        <th>Fecha</th>
                        <th>Ticket</th>
                        <th class="text-end">Costo</th>
                        <th width="90"></th>
                    </tr>
                </thead>
                <tbody>
                @forelse($viaje->casetas as $caseta)
                    <tr>
                        <td>
                            {{ $caseta->nombre }}
                            @if($caseta->observaciones)
                                <small class="d-block text-muted">{{ $caseta->observaciones }}</small>
                            @endif
                        </td>
                        <td>{{ optional($caseta->fecha)->format('d/m/Y') ?: '—' }}</td>
                        <td>{{ $caseta->folio_ticket ?: '—' }}</td>
                        <td class="text-end">${{ number_format($caseta->costo, 2) }}</td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('casetas.edit', $caseta) }}"
                                   class="btn btn-warning btn-sm text-white" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('casetas.destroy', $caseta) }}" method="POST"
                                      onsubmit="return confirm('¿Eliminar esta caseta?')">
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
                        <td colspan="5" class="text-center text-muted py-3">
                            Este viaje aún no tiene casetas registradas.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        {{-- Formulario para agregar una caseta --}}
        <form action="{{ route('viajes.casetas.store', $viaje) }}" method="POST" class="row g-2">
            @csrf

            <div class="col-md-3">
                <input type="text" name="nombre" maxlength="120" required
                       value="{{ old('nombre') }}"
                       class="form-control form-control-sm"
                       placeholder="Nombre de la caseta">
            </div>

            <div class="col-md-2">
                <div class="input-group input-group-sm">
                    <span class="input-group-text">$</span>
                    <input type="number" name="costo" step="0.01" min="0" required
                           value="{{ old('costo') }}"
                           class="form-control" placeholder="Costo">
                </div>
            </div>

            <div class="col-md-2">
                <input type="date" name="fecha"
                       value="{{ old('fecha', optional($viaje->fecha_viaje)->format('Y-m-d')) }}"
                       class="form-control form-control-sm">
            </div>

            <div class="col-md-2">
                <input type="text" name="folio_ticket" maxlength="50"
                       value="{{ old('folio_ticket') }}"
                       class="form-control form-control-sm" placeholder="Folio ticket">
            </div>

            <div class="col-md-2">
                <input type="text" name="observaciones" maxlength="255"
                       value="{{ old('observaciones') }}"
                       class="form-control form-control-sm" placeholder="Observaciones">
            </div>

            <div class="col-md-1">
                <button class="btn btn-primary btn-sm w-100" title="Agregar caseta">
                    <i class="bi bi-plus-lg"></i>
                </button>
            </div>

        </form>

    </div>
</div>