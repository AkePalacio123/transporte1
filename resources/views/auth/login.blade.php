<x-guest-layout>

    <h4 class="fw-bold mb-1">Bienvenido</h4>
    <p class="text-muted mb-4">Ingresa tus credenciales para acceder al sistema.</p>

    {{-- Mensaje de estado (ej. después de recuperar contraseña) --}}
    @if (session('status'))
        <div class="alert alert-success py-2">
            {{ session('status') }}
        </div>
    @endif

    {{-- Errores de validación --}}
    @if ($errors->any())
        <div class="alert alert-danger py-2">
            <i class="bi bi-exclamation-triangle me-1"></i>
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label">Correo electrónico</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                <input type="email" id="email" name="email"
                       value="{{ old('email') }}"
                       class="form-control @error('email') is-invalid @enderror"
                       required autofocus autocomplete="username"
                       placeholder="correo@ejemplo.com">
            </div>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Contraseña</label>
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                <input type="password" id="password" name="password"
                       class="form-control @error('password') is-invalid @enderror"
                       required autocomplete="current-password"
                       placeholder="••••••••">
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="form-check">
                <input type="checkbox" id="remember_me" name="remember" class="form-check-input">
                <label for="remember_me" class="form-check-label small">Recordarme</label>
            </div>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="small text-decoration-none">
                    ¿Olvidaste tu contraseña?
                </a>
            @endif
        </div>

        <button type="submit" class="btn btn-login w-100 py-2">
            <i class="bi bi-box-arrow-in-right me-1"></i>
            Iniciar sesión
        </button>

    </form>

    <p class="text-center text-muted mt-4 mb-0" style="font-size:.75rem;">
        &copy; {{ date('Y') }} SIGCT · Uso interno
    </p>

</x-guest-layout>
