@extends('layouts.app')

@section('title','Editar caseta')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">
            <i class="bi bi-cone-striped me-2"></i>Editar caseta
        </h2>
        <a href="{{ route('viajes.show', $caseta->viaje_id) }}" class="btn btn-outline-secondary">
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

            <form action="{{ route('casetas.update', $caseta) }}" method="POST" class="row g-3">
                @csrf
                @method('PUT')

                <div class="col-md-6">
                    <label class="form-label">Nombre de la caseta <span class="text-danger">*</span></label>
                    <input type="text" name="nombre" maxlength="120" required
                           value="{{ old('nombre', $caseta->nombre) }}"
                           class="form-control @error('nombre') is-invalid @enderror">
                    @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-3">
                    <label class="form-label">Costo <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="number" name="costo" step="0.01" min="0" required
                               value="{{ old('costo', $caseta->costo) }}"
                               class="form-control @error('costo') is-invalid @enderror">
                        @error('costo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Fecha</label>
                    <input type="date" name="fecha"
                           value="{{ old('fecha', optional($caseta->fecha)->format('Y-m-d')) }}"
                           class="form-control @error('fecha') is-invalid @enderror">
                    @error('fecha')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Folio del ticket</label>
                    <input type="text" name="folio_ticket" maxlength="50"
                           value="{{ old('folio_ticket', $caseta->folio_ticket) }}"
                           class="form-control @error('folio_ticket') is-invalid @enderror">
                    @error('folio_ticket')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-8">
                    <label class="form-label">Observaciones</label>
                    <input type="text" name="observaciones" maxlength="255"
                           value="{{ old('observaciones', $caseta->observaciones) }}"
                           class="form-control @error('observaciones') is-invalid @enderror">
                    @error('observaciones')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-12 d-flex gap-2">
                    <button class="btn btn-primary">
                        <i class="bi bi-check-circle me-1"></i>Actualizar
                    </button>
                    <a href="{{ route('viajes.show', $caseta->viaje_id) }}" class="btn btn-light">
                        Cancelar
                    </a>
                </div>

            </form>

        </div>
    </div>

</div>
@endsection