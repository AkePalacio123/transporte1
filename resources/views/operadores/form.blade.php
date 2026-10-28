@csrf

<div class="card shadow-sm border-0">

    <div class="card-header bg-white">

        <h5 class="mb-0">
            Información del operador
        </h5>

    </div>

    <div class="card-body">

        <div class="row">

            {{-- Nombre --}}
            <div class="col-md-6 mb-3">

                <label class="form-label fw-semibold">
                    Nombre
                </label>

                <input
                    type="text"
                    name="nombre"
                    value="{{ old('nombre', $operador->nombre ?? '') }}"
                    class="form-control @error('nombre') is-invalid @enderror">

                @error('nombre')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

            </div>

            {{-- Teléfono --}}
            <div class="col-md-6 mb-3">

                <label class="form-label fw-semibold">
                    Teléfono
                </label>

                <input
                    type="text"
                    name="telefono"
                    value="{{ old('telefono', $operador->telefono ?? '') }}"
                    class="form-control @error('telefono') is-invalid @enderror">

                @error('telefono')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

            </div>

            {{-- Licencia --}}
            <div class="col-md-6 mb-3">

                <label class="form-label fw-semibold">
                    Licencia
                </label>

                <input
                    type="text"
                    name="licencia"
                    value="{{ old('licencia', $operador->licencia ?? '') }}"
                    class="form-control @error('licencia') is-invalid @enderror">

                @error('licencia')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

            </div>

            {{-- Vencimiento --}}
            <div class="col-md-6 mb-3">

                <label class="form-label fw-semibold">
                    Vencimiento de licencia
                </label>

                <input
                    type="date"
                    name="vencimiento_licencia"
                    value="{{ old('vencimiento_licencia', $operador->vencimiento_licencia ?? '') }}"
                    class="form-control @error('vencimiento_licencia') is-invalid @enderror">

                @error('vencimiento_licencia')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

            </div>

            {{-- Estatus --}}
            <div class="col-md-6">

                <label class="form-label fw-semibold">
                    Estatus
                </label>

                <select
                    name="estatus"
                    class="form-select">

                    <option value="Activo"
                        @selected(old('estatus', $operador->estatus ?? '') == 'Activo')>
                        Activo
                    </option>

                    <option value="Suspendido"
                        @selected(old('estatus', $operador->estatus ?? '') == 'Suspendido')>
                        Suspendido
                    </option>

                    <option value="Inactivo"
                        @selected(old('estatus', $operador->estatus ?? '') == 'Inactivo')>
                        Inactivo
                    </option>

                </select>

            </div>

        </div>

    </div>

    <div class="card-footer bg-white">

        <button type="submit" class="btn btn-primary">
            <i class="bi bi-floppy-fill me-2"></i>
            Guardar Operador
        </button>

        <a href="{{ route('operadores.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>
            Cancelar
        </a>

    </div>

</div>