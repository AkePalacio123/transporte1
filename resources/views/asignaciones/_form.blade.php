@php $a = $asignacion ?? null; @endphp

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
        <i class="bi bi-diagram-3 me-2"></i>Datos de la asignación
    </div>
    <div class="card-body row g-3">

        <div class="col-md-6">
            <label class="form-label">Operador <span class="text-danger">*</span></label>
            <select name="operador_id" class="form-select @error('operador_id') is-invalid @enderror" required>
                <option value="">-- Selecciona --</option>
                @foreach($operadores as $operador)
                    <option value="{{ $operador->id }}"
                        @selected(old('operador_id', $a->operador_id ?? '') == $operador->id)>
                        {{ $operador->nombre ?? $operador->nombre_completo ?? 'Operador #'.$operador->id }}
                    </option>
                @endforeach
            </select>
            @error('operador_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-3">
            <label class="form-label">Tracto <span class="text-danger">*</span></label>
            <select name="tracto_id" class="form-select @error('tracto_id') is-invalid @enderror" required>
                <option value="">-- Selecciona --</option>
                @foreach($tractos as $tracto)
                    <option value="{{ $tracto->id }}"
                        @selected(old('tracto_id', $a->tracto_id ?? '') == $tracto->id)>
                        {{ $tracto->serie }} - {{ $tracto->placa }}
                    </option>
                @endforeach
            </select>
            @error('tracto_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-3">
            <label class="form-label">Equipo</label>
            <select name="equipo_id" class="form-select @error('equipo_id') is-invalid @enderror">
                <option value="">-- Sin equipo --</option>
                @foreach($equipos as $equipo)
                    <option value="{{ $equipo->id }}"
                        @selected(old('equipo_id', $a->equipo_id ?? '') == $equipo->id)>
                        {{ $equipo->serie }} - {{ $equipo->placa }}
                    </option>
                @endforeach
            </select>
            @error('equipo_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-3">
            <label class="form-label">Fecha de inicio <span class="text-danger">*</span></label>
            <input type="date" name="fecha_inicio" required
                   value="{{ old('fecha_inicio', optional($a->fecha_inicio ?? null)->format('Y-m-d') ?: date('Y-m-d')) }}"
                   class="form-control @error('fecha_inicio') is-invalid @enderror">
            @error('fecha_inicio')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-3">
            <label class="form-label">Fecha de fin</label>
            <input type="date" name="fecha_fin"
                   value="{{ old('fecha_fin', optional($a->fecha_fin ?? null)->format('Y-m-d')) }}"
                   class="form-control @error('fecha_fin') is-invalid @enderror">
            <div class="form-text">Vacía = sigue vigente.</div>
            @error('fecha_fin')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-6 d-flex align-items-center">
            <div class="form-check form-switch mt-3">
                <input type="hidden" name="activa" value="0">
                <input type="checkbox" name="activa" value="1" id="activa"
                       class="form-check-input"
                       @checked(old('activa', $a->activa ?? true))>
                <label class="form-check-label" for="activa">
                    Asignación activa
                    <small class="text-muted d-block">
                        Al activarla, se cierran las asignaciones previas de ese operador y ese tracto.
                    </small>
                </label>
            </div>
        </div>

        <div class="col-12">
            <label class="form-label">Observaciones</label>
            <textarea name="observaciones" rows="2" maxlength="1000"
                      class="form-control @error('observaciones') is-invalid @enderror">{{ old('observaciones', $a->observaciones ?? '') }}</textarea>
            @error('observaciones')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

    </div>
</div>