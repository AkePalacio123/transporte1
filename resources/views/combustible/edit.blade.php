@extends('layouts.app')

@section('title','Editar carga de combustible')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">
            <i class="bi bi-fuel-pump me-2"></i>Editar carga de combustible
        </h2>
        <a href="{{ route('viajes.show', $combustible->viaje_id) }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Volver al viaje
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body">

            <form action="{{ route('combustible.update', $combustible) }}" method="POST" class="row g-3">
                @csrf
                @method('PUT')

                <div class="col-md-6">
                    <label class="form-label">Gasolinera <span class="text-danger">*</span></label>
                    <input type="text" name="estacion" maxlength="120" required
                           value="{{ old('estacion', $combustible->estacion) }}"
                           class="form-control @error('estacion') is-invalid @enderror">
                    @error('estacion')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Fecha <span class="text-danger">*</span></label>
                    <input type="date" name="fecha" required
                           value="{{ old('fecha', optional($combustible->fecha)->format('Y-m-d')) }}"
                           class="form-control @error('fecha') is-invalid @enderror">
                    @error('fecha')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Litros <span class="text-danger">*</span></label>
                    <input type="number" name="litros" step="0.01" min="0.01" required
                           value="{{ old('litros', $combustible->litros) }}"
                           class="form-control @error('litros') is-invalid @enderror">
                    @error('litros')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Total <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="number" name="total" step="0.01" min="0" required
                               value="{{ old('total', $combustible->total) }}"
                               class="form-control @error('total') is-invalid @enderror">
                        @error('total')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Precio por litro</label>
                    <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="number" name="precio_litro" step="0.01" min="0"
                               value="{{ old('precio_litro', $combustible->precio_litro) }}"
                               class="form-control @error('precio_litro') is-invalid @enderror">
                        @error('precio_litro')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-text">Si lo dejas vacío se calcula solo.</div>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Kilometraje</label>
                    <input type="number" name="kilometraje" min="0"
                           value="{{ old('kilometraje', $combustible->kilometraje) }}"
                           class="form-control @error('kilometraje') is-invalid @enderror">
                    @error('kilometraje')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Folio de factura</label>
                    <input type="text" name="folio_factura" maxlength="50"
                           value="{{ old('folio_factura', $combustible->folio_factura) }}"
                           class="form-control @error('folio_factura') is-invalid @enderror">
                    @error('folio_factura')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-12">
                    <label class="form-label">Observaciones</label>
                    <input type="text" name="observaciones" maxlength="255"
                           value="{{ old('observaciones', $combustible->observaciones) }}"
                           class="form-control @error('observaciones') is-invalid @enderror">
                    @error('observaciones')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-12 d-flex gap-2">
                    <button class="btn btn-primary">
                        <i class="bi bi-check-circle me-1"></i>Actualizar
                    </button>
                    <a href="{{ route('viajes.show', $combustible->viaje_id) }}" class="btn btn-light">
                        Cancelar
                    </a>
                </div>

            </form>

        </div>
    </div>

</div>
@endsection