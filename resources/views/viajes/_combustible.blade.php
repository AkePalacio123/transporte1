{{-- Bloque de combustible: se incluye dentro de viajes/show.blade.php --}}
<div class="card border-0 shadow-sm">

    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <span class="fw-bold">
            <i class="bi bi-fuel-pump me-2"></i>Combustible
        </span>
        <span>
            <span class="badge bg-secondary fs-6 me-1">
                {{ number_format($viaje->cargasCombustible->sum('litros'), 2) }} L
            </span>
            <span class="badge bg-dark fs-6">
                Total: ${{ number_format($viaje->cargasCombustible->sum('total'), 2) }}
            </span>
        </span>
    </div>

    <div class="card-body">

        <div class="table-responsive mb-3">
            <table class="table table-sm table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Gasolinera</th>
                        <th>Fecha</th>
                        <th class="text-end">Litros</th>
                        <th class="text-end">$/L</th>
                        <th class="text-end">Total</th>
                        <th class="text-end">Km</th>
                        <th>Factura</th>
                        <th width="90"></th>
                    </tr>
                </thead>
                <tbody>
                @forelse($viaje->cargasCombustible as $carga)
                    <tr>
                        <td>
                            {{ $carga->estacion }}
                            @if($carga->observaciones)
                                <small class="d-block text-muted">{{ $carga->observaciones }}</small>
                            @endif
                        </td>
                        <td>{{ optional($carga->fecha)->format('d/m/Y') }}</td>
                        <td class="text-end">{{ number_format($carga->litros, 2) }}</td>
                        <td class="text-end">${{ number_format($carga->precio_litro, 2) }}</td>
                        <td class="text-end">${{ number_format($carga->total, 2) }}</td>
                        <td class="text-end">{{ $carga->kilometraje ? number_format($carga->kilometraje) : '—' }}</td>
                        <td>{{ $carga->folio_factura ?: '—' }}</td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('combustible.edit', $carga) }}"
                                   class="btn btn-warning btn-sm text-white" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('combustible.destroy', $carga) }}" method="POST"
                                      onsubmit="return confirm('¿Eliminar esta carga de combustible?')">
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
                        <td colspan="8" class="text-center text-muted py-3">
                            Este viaje aún no tiene cargas de combustible registradas.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        {{-- Formulario para agregar una carga --}}
        <form action="{{ route('viajes.combustible.store', $viaje) }}" method="POST" class="row g-2">
            @csrf

            <div class="col-md-2">
                <input type="text" name="estacion" maxlength="120" required
                       value="{{ old('estacion') }}"
                       class="form-control form-control-sm" placeholder="Gasolinera">
            </div>

            <div class="col-md-2">
                <input type="date" name="fecha" required
                       value="{{ old('fecha', optional($viaje->fecha_viaje)->format('Y-m-d')) }}"
                       class="form-control form-control-sm">
            </div>

            <div class="col-md-1">
                <input type="number" name="litros" step="0.01" min="0.01" required
                       value="{{ old('litros') }}"
                       class="form-control form-control-sm" placeholder="Litros">
            </div>

            <div class="col-md-2">
                <div class="input-group input-group-sm">
                    <span class="input-group-text">$</span>
                    <input type="number" name="total" step="0.01" min="0" required
                           value="{{ old('total') }}"
                           class="form-control" placeholder="Total">
                </div>
            </div>

            <div class="col-md-2">
                <input type="number" name="kilometraje" min="0"
                       value="{{ old('kilometraje') }}"
                       class="form-control form-control-sm" placeholder="Kilometraje">
            </div>

            <div class="col-md-2">
                <input type="text" name="folio_factura" maxlength="50"
                       value="{{ old('folio_factura') }}"
                       class="form-control form-control-sm" placeholder="Folio factura">
            </div>

            <div class="col-md-1">
                <button class="btn btn-primary btn-sm w-100" title="Agregar carga">
                    <i class="bi bi-plus-lg"></i>
                </button>
            </div>

        </form>

        <small class="text-muted d-block mt-2">
            El precio por litro se calcula solo a partir del total y los litros.
        </small>

    </div>
</div>