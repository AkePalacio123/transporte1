@php $u = $usuario ?? null; @endphp

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
        <i class="bi bi-person-badge me-2"></i>Datos del usuario
    </div>
    <div class="card-body row g-3">
        <div class="col-md-6">
            <label class="form-label">Nombre <span class="text-danger">*</span></label>
            <input type="text" name="name" maxlength="100" required
                   value="{{ old('name', $u->name ?? '') }}"
                   class="form-control @error('name') is-invalid @enderror">
            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6">
            <label class="form-label">Correo <span class="text-danger">*</span></label>
            <input type="email" name="email" maxlength="150" required
                   value="{{ old('email', $u->email ?? '') }}"
                   class="form-control @error('email') is-invalid @enderror">
            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6">
            <label class="form-label">Rol <span class="text-danger">*</span></label>
            <select name="rol" class="form-select @error('rol') is-invalid @enderror" required>
                @foreach($roles as $rol)
                    <option value="{{ $rol }}" @selected(old('rol', $u->rol ?? 'Capturista') == $rol)>
                        {{ $rol }}
                    </option>
                @endforeach
            </select>
            <div class="form-text">
                Administrador: acceso total · Capturista: registra y edita · Consulta: solo lectura.
            </div>
            @error('rol')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6 d-flex align-items-center">
            <div class="form-check form-switch mt-3">
                <input type="hidden" name="activo" value="0">
                <input type="checkbox" name="activo" value="1" id="activo"
                       class="form-check-input" @checked(old('activo', $u->activo ?? true))>
                <label class="form-check-label" for="activo">Cuenta activa</label>
            </div>
        </div>
        <div class="col-md-6">
            <label class="form-label">
                Contraseña @if(!$u)<span class="text-danger">*</span>@endif
            </label>
            <input type="password" name="password" {{ $u ? '' : 'required' }}
                   class="form-control @error('password') is-invalid @enderror">
            @if($u)<div class="form-text">Déjala vacía para conservar la actual.</div>@endif
            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6">
            <label class="form-label">
                Confirmar contraseña @if(!$u)<span class="text-danger">*</span>@endif
            </label>
            <input type="password" name="password_confirmation" {{ $u ? '' : 'required' }}
                   class="form-control">
        </div>
    </div>
</div>

{{-- ============ MÓDULOS PERMITIDOS ============ --}}
<div class="card border-0 shadow-sm mb-3">
    <div class="card-header bg-white fw-bold">
        <i class="bi bi-grid-3x3-gap me-2"></i>Módulos permitidos
    </div>
    <div class="card-body">

        <div class="alert alert-light border small">
            <i class="bi bi-info-circle me-1"></i>
            Marca los módulos a los que este usuario tendrá acceso.
            <strong>Si no marcas ninguno, el usuario verá todos los módulos.</strong>
            El Administrador siempre tiene acceso completo, sin importar lo que se marque aquí.
        </div>

        @php
            // Módulos que el usuario ya tiene (para marcar las casillas al editar)
            $modulosActuales = old('modulos_permitidos', $u->modulos_permitidos ?? []);
            if (!is_array($modulosActuales)) { $modulosActuales = []; }
        @endphp

        <div class="row">
            @foreach(\App\Models\User::MODULOS as $clave => $nombre)
                <div class="col-md-4 col-sm-6 mb-2">
                    <div class="form-check">
                        <input type="checkbox"
                               name="modulos_permitidos[]"
                               value="{{ $clave }}"
                               id="modulo_{{ $clave }}"
                               class="form-check-input"
                               @checked(in_array($clave, $modulosActuales, true))>
                        <label class="form-check-label" for="modulo_{{ $clave }}">
                            {{ $nombre }}
                        </label>
                    </div>
                </div>
            @endforeach
        </div>

    </div>
</div>