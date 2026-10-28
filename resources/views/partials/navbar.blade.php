<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container-fluid">
        <h4 class="mb-0 fw-bold">
            Sistema Integral de Gestión y Control de Transporte
        </h4>
        <div class="d-flex align-items-center">
            <i class="bi bi-bell fs-4 me-4"></i>
            <span class="fw-semibold">
                {{ auth()->user()?->name ?? 'Invitado' }}
                @auth
                    <small class="text-muted d-block text-end" style="font-size:.7rem">
                        {{ auth()->user()->rol }}
                    </small>
                @endauth
            </span>
        </div>
    </div>
</nav>