@csrf

<div class="card shadow-sm border-0">

    <div class="card-header bg-white">

        <h5 class="mb-0">

            Información de la empresa

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
                    value="{{ old('nombre', $empresa->nombre ?? '') }}"
                    class="form-control @error('nombre') is-invalid @enderror">

                @error('nombre')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

            </div>

            {{-- RFC --}}
            <div class="col-md-6 mb-3">

                <label class="form-label fw-semibold">
                    RFC
                </label>

                <input
                    type="text"
                    name="rfc"
                    value="{{ old('rfc', $empresa->rfc ?? '') }}"
                    class="form-control @error('rfc') is-invalid @enderror">

                @error('rfc')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror

            </div>

            {{-- Contacto --}}
            <div class="col-md-6 mb-3">

                <label class="form-label fw-semibold">
                    Contacto
                </label>

                <input
                    type="text"
                    name="contacto"
                    value="{{ old('contacto', $empresa->contacto ?? '') }}"
                    class="form-control">

            </div>

            {{-- Teléfono --}}
            <div class="col-md-6 mb-3">

                <label class="form-label fw-semibold">
                    Teléfono
                </label>

                <input
                    type="text"
                    name="telefono"
                    value="{{ old('telefono', $empresa->telefono ?? '') }}"
                    class="form-control">

            </div>

            {{-- Correo --}}
            <div class="col-md-6 mb-3">

                <label class="form-label fw-semibold">
                    Correo electrónico
                </label>

                <input
                    type="email"
                    name="correo"
                    value="{{ old('correo', $empresa->correo ?? '') }}"
                    class="form-control">

            </div>

            {{-- Estatus --}}
            <div class="col-md-6 mb-3">

                <label class="form-label fw-semibold">
                    Estatus
                </label>

                <select
                    name="estatus"
                    class="form-select">

                    <option
                        value="Activa"
                        @selected(old('estatus', $empresa->estatus ?? '') == 'Activa')>

                        Activa

                    </option>

                    <option
                        value="Inactiva"
                        @selected(old('estatus', $empresa->estatus ?? '') == 'Inactiva')>

                        Inactiva

                    </option>

                </select>

            </div>

            {{-- Observaciones --}}
            <div class="col-12">

                <label class="form-label fw-semibold">

                    Observaciones

                </label>

                <textarea
                    name="observaciones"
                    rows="4"
                    class="form-control">{{ old('observaciones', $empresa->observaciones ?? '') }}</textarea>

            </div>

        </div>

    </div>

    <div class="card-footer bg-white">

        <button
            class="btn btn-primary">

            <i class="bi bi-floppy-fill me-2"></i>

            Guardar Empresa

        </button>

        <a
            href="{{ route('empresas.index') }}"
            class="btn btn-outline-secondary">

            <i class="bi bi-arrow-left me-2"></i>

            Cancelar

        </a>

    </div>

</div>