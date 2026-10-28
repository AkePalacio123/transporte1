{{-- Campos compartidos por crear y editar --}}
@php
    $v = $viaje ?? null;
@endphp

@if($errors->any())
    <div class="alert alert-danger">
        <i class="bi bi-exclamation-triangle me-2"></i>
        Revisa los siguientes datos:
        <ul class="mb-0 mt-2">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- ============ IDENTIFICACIÓN ============ --}}
<div class="card border-0 shadow-sm mb-3">
    <div class="card-header bg-white fw-bold">
        <i class="bi bi-hash me-2"></i>Identificación
    </div>
    <div class="card-body row g-3">

        <div class="col-md-4">
            <label class="form-label">Empresa <span class="text-danger">*</span></label>
            <select name="empresa_id" class="form-select @error('empresa_id') is-invalid @enderror" required>
                <option value="">-- Selecciona --</option>
                @foreach($empresas as $empresa)
                    <option value="{{ $empresa->id }}"
                        @selected(old('empresa_id', $v->empresa_id ?? '') == $empresa->id)>
                        {{ $empresa->nombre }}
                    </option>
                @endforeach
            </select>
            @error('empresa_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-4">
            <label class="form-label">Folio</label>
            <input type="text" name="folio" maxlength="30"
                   value="{{ old('folio', $v->folio ?? '') }}"
                   class="form-control @error('folio') is-invalid @enderror"
                   placeholder="Solo si la empresa lo usa">
            <div class="form-text">Opcional. EMMSA no maneja folio.</div>
            @error('folio')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-4">
            <label class="form-label">Carta porte <span class="text-danger">*</span></label>
            <input type="text" name="cataporte" maxlength="30" required
                   value="{{ old('cataporte', $v->cataporte ?? '') }}"
                   class="form-control @error('cataporte') is-invalid @enderror">
            @error('cataporte')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-4">
            <label class="form-label">Tipo de viaje <span class="text-danger">*</span></label>
            <select name="tipo_viaje" class="form-select @error('tipo_viaje') is-invalid @enderror" required>
                @foreach(['Normal', 'Retorno'] as $tipo)
                    <option value="{{ $tipo }}"
                        @selected(old('tipo_viaje', $v->tipo_viaje ?? 'Normal') == $tipo)>
                        {{ $tipo }}
                    </option>
                @endforeach
            </select>
            @error('tipo_viaje')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-4">
            <label class="form-label">Fecha del viaje <span class="text-danger">*</span></label>
            <input type="date" name="fecha_viaje" id="fecha_viaje" required
                   value="{{ old('fecha_viaje', optional($v->fecha_viaje ?? null)->format('Y-m-d')) }}"
                   class="form-control @error('fecha_viaje') is-invalid @enderror">
            @error('fecha_viaje')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-4">
            <label class="form-label">Fecha de captura <span class="text-danger">*</span></label>
            <input type="date" name="fecha_captura" required
                   value="{{ old('fecha_captura', optional($v->fecha_captura ?? null)->format('Y-m-d') ?: date('Y-m-d')) }}"
                   class="form-control @error('fecha_captura') is-invalid @enderror">
            @error('fecha_captura')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

    </div>
</div>

{{-- ============ ASIGNACIÓN ============ --}}
<div class="card border-0 shadow-sm mb-3">
    <div class="card-header bg-white fw-bold">
        <i class="bi bi-people me-2"></i>Asignación
    </div>
    <div class="card-body row g-3">

        <div class="col-md-4">
            <label class="form-label">Operador <span class="text-danger">*</span></label>
            <select name="operador_id" id="operador_id"
                    data-url-asignacion="{{ url('api/operador') }}"
                    class="form-select @error('operador_id') is-invalid @enderror" required>
                <option value="">-- Selecciona --</option>
                @foreach($operadores as $operador)
                    <option value="{{ $operador->id }}"
                        @selected(old('operador_id', $v->operador_id ?? '') == $operador->id)>
                        {{ $operador->nombre ?? $operador->nombre_completo ?? 'Operador #'.$operador->id }}
                    </option>
                @endforeach
            </select>
            @error('operador_id')<div class="invalid-feedback">{{ $message }}</div>@enderror

            {{-- Aviso dinámico de asignación --}}
            <div id="aviso_asignacion" class="small mt-2" style="display:none;"></div>
        </div>

        <div class="col-md-4">
            <label class="form-label">Tracto <span class="text-danger">*</span></label>
            <select name="tracto_id" id="tracto_id" class="form-select @error('tracto_id') is-invalid @enderror" required>
                <option value="">-- Selecciona --</option>
                @foreach($tractos as $tracto)
                    <option value="{{ $tracto->id }}"
                        @selected(old('tracto_id', $v->tracto_id ?? '') == $tracto->id)>
                        {{ $tracto->serie ?? 'Tracto #'.$tracto->id }}
                        @if(!empty($tracto->placa)) - {{ $tracto->placa }} @endif
                    </option>
                @endforeach
            </select>
            @error('tracto_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-4">
            <label class="form-label">Equipo / Caja</label>
            <select name="equipo_id" id="equipo_id" class="form-select @error('equipo_id') is-invalid @enderror">
                <option value="">-- Sin equipo --</option>
                @foreach($equipos as $equipo)
                    <option value="{{ $equipo->id }}"
                        @selected(old('equipo_id', $v->equipo_id ?? '') == $equipo->id)>
                        {{ $equipo->serie ?? 'Equipo #'.$equipo->id }}
                        @if(!empty($equipo->placa)) - {{ $equipo->placa }} @endif
                    </option>
                @endforeach
            </select>
            @error('equipo_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

    </div>
</div>

{{-- ============ RUTA ============ --}}
<div class="card border-0 shadow-sm mb-3">
    <div class="card-header bg-white fw-bold">
        <i class="bi bi-geo-alt me-2"></i>Ruta
    </div>
    <div class="card-body row g-3">

        <div class="col-md-6">
            <label class="form-label">Origen <span class="text-danger">*</span></label>
            <input type="text" name="origen" maxlength="150" required
                   value="{{ old('origen', $v->origen ?? '') }}"
                   class="form-control @error('origen') is-invalid @enderror">
            @error('origen')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-6">
            <label class="form-label">Destino <span class="text-danger">*</span></label>
            <input type="text" name="destino" maxlength="150" required
                   value="{{ old('destino', $v->destino ?? '') }}"
                   class="form-control @error('destino') is-invalid @enderror">
            @error('destino')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-4">
            <label class="form-label">Kilómetros recorridos (ida y vuelta)</label>
            <input type="number" name="kilometros" min="0" max="100000"
                value="{{ old('kilometros', $v->kilometros ?? '') }}"
                class="form-control @error('kilometros') is-invalid @enderror"
                placeholder="Ej. 1200">
            <div class="form-text">Se usa para calcular el mantenimiento del tracto.</div>
            @error('kilometros')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

    </div>
</div>

{{-- ============ PAGO Y ESTATUS ============ --}}
<div class="card border-0 shadow-sm mb-3">
    <div class="card-header bg-white fw-bold">
        <i class="bi bi-cash-coin me-2"></i>Pago y estatus
    </div>
    <div class="card-body row g-3">

        <div class="col-md-3">
            <label class="form-label">Monto <span class="text-danger">*</span></label>
            <div class="input-group">
                <span class="input-group-text">$</span>
                <input type="number" name="pago" step="0.01" min="0" required
                       value="{{ old('pago', $v->pago ?? '0.00') }}"
                       class="form-control @error('pago') is-invalid @enderror">
                @error('pago')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="col-md-3">
            <label class="form-label">Estatus del viaje <span class="text-danger">*</span></label>
            <select name="estatus" class="form-select @error('estatus') is-invalid @enderror" required>
                @foreach(['Pendiente', 'En ruta', 'Finalizado', 'Cancelado'] as $op)
                    <option value="{{ $op }}"
                        @selected(old('estatus', $v->estatus ?? 'Pendiente') == $op)>
                        {{ $op }}
                    </option>
                @endforeach
            </select>
            @error('estatus')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-3">
            <label class="form-label">Estatus de pago <span class="text-danger">*</span></label>
            <select name="estatus_pago" id="estatus_pago"
                    class="form-select @error('estatus_pago') is-invalid @enderror" required>
                @foreach(['Pendiente', 'Pagado'] as $op)
                    <option value="{{ $op }}"
                        @selected(old('estatus_pago', $v->estatus_pago ?? 'Pendiente') == $op)>
                        {{ $op }}
                    </option>
                @endforeach
            </select>
            @error('estatus_pago')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-3">
            <label class="form-label">Fecha de pago</label>
            <input type="date" name="fecha_pago" id="fecha_pago"
                   value="{{ old('fecha_pago', optional($v->fecha_pago ?? null)->format('Y-m-d')) }}"
                   class="form-control @error('fecha_pago') is-invalid @enderror">
            <div class="form-text">Requerida si el viaje está pagado.</div>
            @error('fecha_pago')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-4">
            <label class="form-label">Fecha límite de pago</label>
            <input type="date" name="fecha_limite_pago" id="fecha_limite_pago"
                   value="{{ old('fecha_limite_pago', optional($v->fecha_limite_pago ?? null)->format('Y-m-d')) }}"
                   class="form-control @error('fecha_limite_pago') is-invalid @enderror">
            <div class="form-text">
                Se sugiere un mes después del viaje. Puedes cambiarla.
            </div>
            @error('fecha_limite_pago')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

    </div>
</div>

{{-- ============ BITÁCORA Y DOCUMENTOS ============ --}}
<div class="card border-0 shadow-sm mb-3">
    <div class="card-header bg-white fw-bold">
        <i class="bi bi-file-earmark-pdf me-2"></i>Bitácora y documentos
    </div>
    <div class="card-body row g-3">

        <div class="col-md-4">
            <label class="form-label">Estatus de bitácora <span class="text-danger">*</span></label>
            <select name="estatus_bitacora" id="estatus_bitacora"
                    class="form-select @error('estatus_bitacora') is-invalid @enderror" required>
                @foreach(['No entregada', 'Entregada'] as $op)
                    <option value="{{ $op }}"
                        @selected(old('estatus_bitacora', $v->estatus_bitacora ?? 'No entregada') == $op)>
                        {{ $op }}
                    </option>
                @endforeach
            </select>
            @error('estatus_bitacora')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-4">
            <label class="form-label">Entregado a</label>
            <input type="text" name="entregado_a" maxlength="100"
                   value="{{ old('entregado_a', $v->entregado_a ?? '') }}"
                   class="form-control @error('entregado_a') is-invalid @enderror">
            @error('entregado_a')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-4">
            <label class="form-label">Fecha de entrega</label>
            <input type="date" name="fecha_entrega_bitacora"
                   value="{{ old('fecha_entrega_bitacora', optional($v->fecha_entrega_bitacora ?? null)->format('Y-m-d')) }}"
                   class="form-control @error('fecha_entrega_bitacora') is-invalid @enderror">
            @error('fecha_entrega_bitacora')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="col-md-6">
            <label class="form-label">Archivo PDF de la bitácora</label>
            <input type="file" name="archivo_bitacora" accept="application/pdf"
                   class="form-control @error('archivo_bitacora') is-invalid @enderror">
            <div class="form-text">Solo PDF, máximo 10 MB.</div>
            @error('archivo_bitacora')<div class="invalid-feedback">{{ $message }}</div>@enderror

            @if(!empty($v->archivo_bitacora))
                <div class="mt-2">
                    <a href="{{ asset('storage/'.$v->archivo_bitacora) }}"
                       target="_blank" class="btn btn-sm btn-outline-danger">
                        <i class="bi bi-file-earmark-pdf me-1"></i>Ver PDF actual
                    </a>
                    <small class="text-muted d-block mt-1">
                        Si subes uno nuevo, este se reemplaza.
                    </small>
                </div>
            @endif
        </div>

        <div class="col-md-6">
            <label class="form-label">Observaciones</label>
            <textarea name="observaciones" rows="3" maxlength="1000"
                      class="form-control @error('observaciones') is-invalid @enderror">{{ old('observaciones', $v->observaciones ?? '') }}</textarea>
            @error('observaciones')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

    </div>
</div>

{{-- ============ SCRIPTS DEL FORMULARIO ============ --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    /* ---------- Autocompletado de tracto y equipo según el operador ---------- */
    const selectOperador = document.getElementById('operador_id');
    const selectTracto   = document.getElementById('tracto_id');
    const selectEquipo   = document.getElementById('equipo_id');
    const aviso          = document.getElementById('aviso_asignacion');
    const urlBase        = selectOperador.dataset.urlAsignacion;

    function mostrarAviso(texto, tipo) {
        aviso.style.display = 'block';
        aviso.className = 'small mt-2 alert py-1 px-2 ' +
            (tipo === 'ok' ? 'alert-success' : 'alert-warning');
        aviso.innerHTML = texto;
    }

    function ocultarAviso() {
        aviso.style.display = 'none';
        aviso.innerHTML = '';
    }

    selectOperador.addEventListener('change', function () {
        const operadorId = this.value;

        if (!operadorId) {
            ocultarAviso();
            return;
        }

        fetch(urlBase + '/' + operadorId + '/asignacion')
            .then(resp => resp.json())
            .then(data => {
                if (data.tiene) {
                    selectTracto.value = data.tracto_id ?? '';
                    selectEquipo.value = data.equipo_id ?? '';
                    mostrarAviso(
                        '<i class="bi bi-check-circle me-1"></i>' +
                        'Unidad asignada cargada automáticamente. Puedes cambiarla si es necesario.',
                        'ok'
                    );
                } else {
                    mostrarAviso(
                        '<i class="bi bi-exclamation-triangle me-1"></i>' +
                        'Este operador no tiene una unidad asignada. Selecciónala manualmente.',
                        'warn'
                    );
                }
            })
            .catch(() => {
                ocultarAviso();
            });
    });

    /* ---------- Sugerir fecha límite de pago (fecha del viaje + 1 mes) ---------- */
    const fechaViaje  = document.getElementById('fecha_viaje');
    const fechaLimite = document.getElementById('fecha_limite_pago');

    function sumarUnMes(fechaStr) {
        // fechaStr viene como "YYYY-MM-DD"
        const partes = fechaStr.split('-');
        const anio = parseInt(partes[0], 10);
        const mes  = parseInt(partes[1], 10) - 1; // 0-11
        const dia  = parseInt(partes[2], 10);

        const d = new Date(anio, mes, dia);
        d.setMonth(d.getMonth() + 1);

        // Formatear de vuelta a YYYY-MM-DD
        const y  = d.getFullYear();
        const m  = String(d.getMonth() + 1).padStart(2, '0');
        const dd = String(d.getDate()).padStart(2, '0');
        return `${y}-${m}-${dd}`;
    }

    fechaViaje.addEventListener('change', function () {
        if (!this.value) return;

        // Solo sugiere si el campo de límite está vacío,
        // para no pisar una fecha que el usuario ya puso a mano
        // (importante al editar un viaje existente).
        if (!fechaLimite.value) {
            fechaLimite.value = sumarUnMes(this.value);
        }
    });

});
</script>