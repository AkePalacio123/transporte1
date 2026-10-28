@php $item = $tracto ?? null; @endphp

@if($errors->any())
    <div class="alert alert-danger">
        <i class="bi bi-exclamation-triangle me-2"></i>Revisa los siguientes datos:
        <ul class="mb-0 mt-2">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
@endif

<div class="card border-0 shadow-sm mb-3">
    <div class="card-header bg-white fw-bold">
        <i class="bi bi-truck-front me-2"></i>Datos del tracto
    </div>
    <div class="card-body row g-3">

        <div class="col-md-4">
            <label class="form-label">Número de serie (VIN) <span class="text-danger">*</span></label>
            <input type="text" name="serie" maxlength="50" required
                   value="{{ old('serie', $item->serie ?? '') }}"
                   class="form-control text-uppercase @error('serie') is-invalid @enderror">
            @error('serie')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-4">
            <label class="form-label">Placa <span class="text-danger">*</span></label>
            <input type="text" name="placa" maxlength="20" required
                   value="{{ old('placa', $item->placa ?? '') }}"
                   class="form-control text-uppercase @error('placa') is-invalid @enderror">
            @error('placa')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-4">
            <label class="form-label">Estatus <span class="text-danger">*</span></label>
            <select name="estatus" class="form-select @error('estatus') is-invalid @enderror" required>
                @foreach(['Activo','Inactivo','Mantenimiento','Baja'] as $op)
                    <option value="{{ $op }}" @selected(old('estatus', $item->estatus ?? 'Activo') == $op)>{{ $op }}</option>
                @endforeach
            </select>
            @error('estatus')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-4">
            <label class="form-label">Marca <span class="text-danger">*</span></label>
            <input type="text" name="marca" maxlength="60" required
                   value="{{ old('marca', $item->marca ?? '') }}"
                   class="form-control @error('marca') is-invalid @enderror">
            @error('marca')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-4">
            <label class="form-label">Modelo <span class="text-danger">*</span></label>
            <input type="text" name="modelo" maxlength="60" required
                   value="{{ old('modelo', $item->modelo ?? '') }}"
                   class="form-control @error('modelo') is-invalid @enderror">
            @error('modelo')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-4">
            <label class="form-label">Año <span class="text-danger">*</span></label>
            <input type="number" name="anio" min="1980" max="{{ date('Y') + 1 }}" required
                   value="{{ old('anio', $item->anio ?? '') }}"
                   class="form-control @error('anio') is-invalid @enderror" placeholder="Ej. 2015">
            @error('anio')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-4">
            <label class="form-label">No. tarjeta de circulación</label>
            <input type="text" name="tarjeta_circulacion" maxlength="60"
                   value="{{ old('tarjeta_circulacion', $item->tarjeta_circulacion ?? '') }}"
                   class="form-control @error('tarjeta_circulacion') is-invalid @enderror">
            @error('tarjeta_circulacion')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-12">
            <div class="alert alert-light border small mb-0">
                <i class="bi bi-info-circle me-1"></i>
                Los documentos del tracto (tarjeta de circulación, póliza, factura, etc.)
                se suben desde la pantalla de detalle, en la sección
                <strong>Documentos del tracto</strong>.
            </div>
        </div>

        <div class="col-12">
            <label class="form-label">Observaciones</label>
            <textarea name="observaciones" rows="3" maxlength="1000"
                      class="form-control @error('observaciones') is-invalid @enderror">{{ old('observaciones', $item->observaciones ?? '') }}</textarea>
            @error('observaciones')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

    </div>
</div>