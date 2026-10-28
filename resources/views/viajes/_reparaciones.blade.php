<div class="card border-0 shadow-sm">
    <div class="card-header bg-white fw-bold d-flex justify-content-between align-items-center">
        <span><i class="bi bi-tools me-2"></i>Reparaciones en el viaje</span>
        <span class="badge bg-secondary">
            Total: ${{ number_format($viaje->reparaciones->sum('costo'), 2) }}
        </span>
    </div>
    <div class="card-body">

        {{-- Formulario para agregar (solo si puede editar) --}}
        @if(auth()->user()?->puedeEditar())
            <form action="{{ route('viajes.reparaciones.store', $viaje) }}" method="POST" class="row g-2 mb-3">
                @csrf
                <div class="col-md-4">
                    <label class="form-label small mb-1">Descripción <span class="text-danger">*</span></label>
                    <input type="text" name="descripcion" maxlength="255" required
                           class="form-control" placeholder="Ej. Cambio de llanta, reparación de motor...">
                </div>
                <div class="col-md-2">
                    <label class="form-label small mb-1">Costo <span class="text-danger">*</span></label>
                    <input type="number" name="costo" step="0.01" min="0" required
                           class="form-control" placeholder="0.00">
                </div>
                <div class="col-md-2">
                    <label class="form-label small mb-1">Fecha</label>
                    <input type="date" name="fecha" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label small mb-1">Observaciones</label>
                    <input type="text" name="observaciones" maxlength="500"
                           class="form-control" placeholder="Opcional">
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100" title="Agregar">
                        <i class="bi bi-plus-lg"></i>
                    </button>
                </div>
            </form>
        @endif

        {{-- Lista de reparaciones --}}
        <div class="table-responsive">
            <table class="table table-sm table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Descripción</th>
                        <th>Fecha</th>
                        <th class="text-end">Costo</th>
                        <th>Observaciones</th>
                        @if(auth()->user()?->puedeEditar())
                            <th width="60"></th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                @forelse($viaje->reparaciones as $rep)
                    <tr>
                        <td>{{ $rep->descripcion }}</td>
                        <td><small>{{ optional($rep->fecha)->format('d/m/Y') ?: '—' }}</small></td>
                        <td class="text-end">${{ number_format($rep->costo, 2) }}</td>
                        <td><small class="text-muted">{{ $rep->observaciones ?: '—' }}</small></td>
                        @if(auth()->user()?->puedeEditar())
                            <td>
                                <form action="{{ route('reparaciones.destroy', $rep) }}" method="POST"
                                      onsubmit="return confirm('¿Eliminar esta reparación?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" title="Eliminar">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ auth()->user()?->puedeEditar() ? 5 : 4 }}" class="text-center text-muted py-3">
                            Sin reparaciones registradas en este viaje.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>