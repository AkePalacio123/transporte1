@php
    $m = $mantenimiento ?? null;
    $unidadActual = old('unidad', $m
        ? ($m->tracto_id ? 'tracto-'.$m->tracto_id : 'equipo-'.$m->equipo_id)
        : '');
@endphp

@if($errors->any())
    <div class="alert alert-danger">
        <i class="bi bi-exclamation-triangle me-2"></i>Revisa los siguientes datos:
        <ul class="mb-0 mt-2">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
@endif

{{-- ===== UNIDAD Y TIPO ===== --}}
<div class="card border-0 shadow-sm mb-3">
    <div class="card-header bg-white fw-bold">
        <i class="bi bi-wrench-adjustable me-2"></i>Unidad y tipo de servicio
    </div>
    <div class="card-body row g-3">

        <div class="col-md-5">
            <label class="form-label">Unidad atendida <span class="text-danger">*</span></label>
            <select name="unidad" class="form-select @error('unidad') is-invalid @enderror" required>
                <option value="">-- Selecciona tracto o equipo --</option>
                @php $grupoActual = null; @endphp
                @foreach($unidades as $u)
                    @if($u['grupo'] !== $grupoActual)
                        @if($grupoActual !== null)</optgroup>@endif
                        <optgroup label="{{ $u['grupo'] }}">
                        @php $grupoActual = $u['grupo']; @endphp
                    @endif
                    <option value="{{ $u['valor'] }}" @selected($unidadActual == $u['valor'])>
                        {{ $u['texto'] }}
                    </option>
                @endforeach
                @if($grupoActual !== null)</optgroup>@endif
            </select>
            @error('unidad')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-3">
            <label class="form-label">Tipo <span class="text-danger">*</span></label>
            <select name="tipo" class="form-select @error('tipo') is-invalid @enderror" required>
                @foreach(['Preventivo','Correctivo'] as $op)
                    <option value="{{ $op }}" @selected(old('tipo', $m->tipo ?? 'Preventivo') == $op)>
                        {{ $op }}
                    </option>
                @endforeach
            </select>
            @error('tipo')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-4">
            <label class="form-label">Estatus <span class="text-danger">*</span></label>
            <select name="estatus" class="form-select @error('estatus') is-invalid @enderror" required>
                @foreach(['En taller','Finalizado','Cancelado'] as $op)
                    <option value="{{ $op }}" @selected(old('estatus', $m->estatus ?? 'En taller') == $op)>
                        {{ $op }}
                    </option>
                @endforeach
            </select>
            @error('estatus')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

    </div>
</div>

{{-- ===== TALLER Y FECHAS ===== --}}
<div class="card border-0 shadow-sm mb-3">
    <div class="card-header bg-white fw-bold">
        <i class="bi bi-calendar-check me-2"></i>Taller y fechas
    </div>
    <div class="card-body row g-3">

        <div class="col-md-3">
            <label class="form-label">Fecha de entrada <span class="text-danger">*</span></label>
            <input type="date" name="fecha_entrada" required
                   value="{{ old('fecha_entrada', optional($m->fecha_entrada ?? null)->format('Y-m-d') ?: date('Y-m-d')) }}"
                   class="form-control @error('fecha_entrada') is-invalid @enderror">
            @error('fecha_entrada')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-3">
            <label class="form-label">Fecha de salida</label>
            <input type="date" name="fecha_salida"
                   value="{{ old('fecha_salida', optional($m->fecha_salida ?? null)->format('Y-m-d')) }}"
                   class="form-control @error('fecha_salida') is-invalid @enderror">
            <div class="form-text">Requerida si está finalizado.</div>
            @error('fecha_salida')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-3">
            <label class="form-label">Kilometraje</label>
            <input type="number" name="kilometraje" min="0"
                   value="{{ old('kilometraje', $m->kilometraje ?? '') }}"
                   class="form-control @error('kilometraje') is-invalid @enderror">
            @error('kilometraje')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-3">
            <label class="form-label">Taller</label>
            <input type="text" name="taller" maxlength="120"
                   value="{{ old('taller', $m->taller ?? '') }}"
                   class="form-control @error('taller') is-invalid @enderror">
            @error('taller')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

    </div>
</div>

{{-- ===== SERVICIO Y COSTOS ===== --}}
<div class="card border-0 shadow-sm mb-3">
    <div class="card-header bg-white fw-bold">
        <i class="bi bi-tools me-2"></i>Servicio y costos
    </div>
    <div class="card-body row g-3">

        <div class="col-12">
            <label class="form-label">Descripción del servicio <span class="text-danger">*</span></label>
            <textarea name="descripcion" rows="3" maxlength="2000" required
                      class="form-control @error('descripcion') is-invalid @enderror"
                      placeholder="Detalla el servicio realizado o la falla atendida">{{ old('descripcion', $m->descripcion ?? '') }}</textarea>
            @error('descripcion')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-4">
            <label class="form-label">Costo de mano de obra <span class="text-danger">*</span></label>
            <div class="input-group">
                <span class="input-group-text">$</span>
                <input type="number" name="costo_mano_obra" step="0.01" min="0" required
                       value="{{ old('costo_mano_obra', $m->costo_mano_obra ?? '0.00') }}"
                       class="form-control @error('costo_mano_obra') is-invalid @enderror">
                @error('costo_mano_obra')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="form-text">Las piezas se agregan después de guardar.</div>
        </div>

        <div class="col-md-4">
            <label class="form-label">Folio de factura</label>
            <input type="text" name="folio_factura" maxlength="50"
                   value="{{ old('folio_factura', $m->folio_factura ?? '') }}"
                   class="form-control @error('folio_factura') is-invalid @enderror">
            @error('folio_factura')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-4">
            <label class="form-label">PDF de la factura</label>
            <input type="file" name="factura_pdf" accept="application/pdf"
                   class="form-control @error('factura_pdf') is-invalid @enderror">
            <div class="form-text">Solo PDF, máximo 10 MB.</div>
            @error('factura_pdf')<div class="invalid-feedback">{{ $message }}</div>@enderror

            @if(!empty($m->factura_pdf))
                <div class="mt-2">
                    <a href="{{ asset('storage/'.$m->factura_pdf) }}" target="_blank"
                       class="btn btn-sm btn-outline-danger">
                        <i class="bi bi-file-earmark-pdf me-1"></i>Ver PDF actual
                    </a>
                </div>
            @endif
        </div>

        <div class="col-12">
            <label class="form-label">Observaciones</label>
            <textarea name="observaciones" rows="2" maxlength="1000"
                      class="form-control @error('observaciones') is-invalid @enderror">{{ old('observaciones', $m->observaciones ?? '') }}</textarea>
            @error('observaciones')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

    </div>
</div>