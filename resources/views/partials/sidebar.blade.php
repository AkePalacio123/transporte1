<div class="sidebar-sigct text-white">

    {{-- Logo / titulo (fijo arriba) --}}
    <div class="p-3 border-bottom border-secondary sidebar-top">
        <a href="{{ url('/') }}" class="text-white text-decoration-none d-flex align-items-center">
            <img src="/transporte1/public/img/logo1.png" alt="Logo" style="width: 64px; height: 64px; object-fit: contain;" class="me-2">
            <div>
                <div class="fw-bold lh-1">SIGCT</div>
                <small class="text-white-50" style="font-size:.7rem">
                    Gestión de Transporte
                </small>
            </div>
        </a>
    </div>

    {{-- Navegacion (scroll vertical propio) --}}
    <nav class="nav flex-column p-2 sidebar-nav">

        @if(Route::has('dashboard'))
            <a href="{{ route('dashboard') }}"
               class="nav-link sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2 me-2"></i> Dashboard
            </a>
        @endif

        {{-- ===== OPERACIÓN ===== --}}
        @php
            $verOperacion = auth()->user()?->puedeVerModulo('viajes')
                || auth()->user()?->puedeVerModulo('casetas')
                || auth()->user()?->puedeVerModulo('mantenimientos')
                || auth()->user()?->puedeVerModulo('asignaciones')
                || auth()->user()?->puedeVerModulo('guias')
                || auth()->user()?->puedeVerModulo('alertas');
        @endphp

        @if($verOperacion)
            <div class="sidebar-heading">Operación</div>
        @endif

        @if(auth()->user()?->puedeVerModulo('viajes') && Route::has('viajes.index'))
            <a href="{{ route('viajes.index') }}"
               class="nav-link sidebar-link {{ request()->routeIs('viajes.*') ? 'active' : '' }}">
                <i class="bi bi-signpost-split me-2"></i> Viajes
            </a>
        @endif

        @if(auth()->user()?->puedeVerModulo('casetas') && Route::has('casetas.index'))
            <a href="{{ route('casetas.index') }}"
               class="nav-link sidebar-link {{ request()->routeIs('casetas.*') ? 'active' : '' }}">
                <i class="bi bi-cone-striped me-2"></i> Casetas
            </a>
        @endif

        @if(auth()->user()?->puedeVerModulo('mantenimientos') && Route::has('mantenimientos.index'))
            <a href="{{ route('mantenimientos.index') }}"
               class="nav-link sidebar-link {{ request()->routeIs('mantenimientos.*') ? 'active' : '' }}">
                <i class="bi bi-wrench-adjustable me-2"></i> Mantenimientos
            </a>
        @endif

        @if(auth()->user()?->puedeVerModulo('asignaciones') && Route::has('asignaciones.index'))
            <a href="{{ route('asignaciones.index') }}"
               class="nav-link sidebar-link {{ request()->routeIs('asignaciones.*') ? 'active' : '' }}">
                <i class="bi bi-diagram-3 me-2"></i> Asignaciones
            </a>
        @endif

        @if(auth()->user()?->puedeVerModulo('guias') && Route::has('guias.index'))
            <a href="{{ route('guias.index') }}"
               class="nav-link sidebar-link {{ request()->routeIs('guias.*') ? 'active' : '' }}">
                <i class="bi bi-journal-text me-2"></i> Guías
            </a>
        @endif

        @if(auth()->user()?->puedeVerModulo('alertas') && Route::has('alertas.documentos'))
            <a href="{{ route('alertas.documentos') }}"
               class="nav-link sidebar-link {{ request()->routeIs('alertas.documentos') ? 'active' : '' }}">
                <i class="bi bi-file-earmark-medical me-2"></i> Alertas de documentos
            </a>
        @endif

        @if(auth()->user()?->puedeVerModulo('alertas') && Route::has('alertas.mantenimiento'))
            <a href="{{ route('alertas.mantenimiento') }}"
            class="nav-link sidebar-link {{ request()->routeIs('alertas.mantenimiento') ? 'active' : '' }}">
                <i class="bi bi-speedometer me-2"></i> Alertas de mantenimiento
            </a>
        @endif

        {{-- ===== CATÁLOGOS ===== --}}
        @php
            $verCatalogos = auth()->user()?->puedeVerModulo('empresas')
                || auth()->user()?->puedeVerModulo('operadores')
                || auth()->user()?->puedeVerModulo('tractos')
                || auth()->user()?->puedeVerModulo('equipos');
        @endphp

        @if($verCatalogos)
            <div class="sidebar-heading">Catálogos</div>
        @endif

        @if(auth()->user()?->puedeVerModulo('empresas') && Route::has('empresas.index'))
            <a href="{{ route('empresas.index') }}"
               class="nav-link sidebar-link {{ request()->routeIs('empresas.*') ? 'active' : '' }}">
                <i class="bi bi-building me-2"></i> Empresas
            </a>
        @endif

        @if(auth()->user()?->puedeVerModulo('operadores') && Route::has('operadores.index'))
            <a href="{{ route('operadores.index') }}"
               class="nav-link sidebar-link {{ request()->routeIs('operadores.*') ? 'active' : '' }}">
                <i class="bi bi-person-badge me-2"></i> Operadores
            </a>
        @endif

        @if(auth()->user()?->puedeVerModulo('operadores') && Route::has('calificacion.operadores'))
            <a href="{{ route('calificacion.operadores') }}"
               class="nav-link sidebar-link {{ request()->routeIs('calificacion.operadores') ? 'active' : '' }}">
                <i class="bi bi-award me-2"></i> Calificación operadores
            </a>
        @endif

        @if(auth()->user()?->puedeVerModulo('tractos') && Route::has('tractos.index'))
            <a href="{{ route('tractos.index') }}"
               class="nav-link sidebar-link {{ request()->routeIs('tractos.*') ? 'active' : '' }}">
                <i class="bi bi-truck-front me-2"></i> Tractos
            </a>
        @endif

        @if(auth()->user()?->puedeVerModulo('equipos') && Route::has('equipos.index'))
            <a href="{{ route('equipos.index') }}"
               class="nav-link sidebar-link {{ request()->routeIs('equipos.*') ? 'active' : '' }}">
                <i class="bi bi-box-seam me-2"></i> Equipos
            </a>
        @endif

        {{-- ===== ADMINISTRACIÓN ===== --}}
        @auth
            @if(auth()->user()->esAdministrador())
                <div class="sidebar-heading">Administración</div>
                <a href="{{ route('usuarios.index') }}"
                   class="nav-link sidebar-link {{ request()->routeIs('usuarios.*') ? 'active' : '' }}">
                    <i class="bi bi-people-fill me-2"></i> Usuarios
                </a>
            @endif
        @endauth

    </nav>

    {{-- Pie del sidebar: usuario y cerrar sesión (fijo abajo) --}}
    <div class="p-3 border-top border-secondary sidebar-bottom">

        @auth
            <div class="mb-2 small text-white-50">
                <i class="bi bi-person-circle me-1"></i>
                {{ auth()->user()->name }}
            </div>

            <form method="POST" action="{{ route('logout') }}" class="mb-2">
                @csrf
                <button type="submit" class="btn btn-outline-light btn-sm w-100">
                    <i class="bi bi-box-arrow-right me-1"></i> Cerrar sesión
                </button>
            </form>
        @endauth

        <small class="text-white-50" style="font-size:.7rem">
            &copy; {{ date('Y') }} SIGCT
        </small>
    </div>

</div>

<style>
    /* El sidebar se queda pegado, con el alto de la pantalla,
       organizado en columna vertical (logo / menú / footer). */
    .sidebar-sigct {
        background-color: #1e293b;
        position: sticky;
        top: 0;
        height: 100vh;
        display: flex;
        flex-direction: column;   /* <-- VERTICAL, uno debajo de otro */
    }

    /* Logo y footer no se encogen: siempre visibles. */
    .sidebar-sigct .sidebar-top,
    .sidebar-sigct .sidebar-bottom {
        flex-shrink: 0;
    }

    /* El menú ocupa el espacio del medio y tiene SU PROPIO scroll
       vertical si no cabe. Así ves todos los módulos sin que se
       acomoden en horizontal. */
    .sidebar-sigct .sidebar-nav {
        flex: 1 1 auto;
        overflow-y: auto;
        overflow-x: hidden;
        min-height: 0;
        flex-wrap: nowrap;        /* <-- evita que se pasen a los lados */
    }

    /* Barra de scroll delgada, del color del sidebar. */
    .sidebar-sigct .sidebar-nav::-webkit-scrollbar {
        width: 6px;
    }
    .sidebar-sigct .sidebar-nav::-webkit-scrollbar-thumb {
        background-color: #475569;
        border-radius: 3px;
    }
    .sidebar-sigct .sidebar-nav::-webkit-scrollbar-track {
        background: transparent;
    }

    .sidebar-sigct .sidebar-link {
        color: #cbd5e1;
        border-radius: .375rem;
        padding: .55rem .75rem;
        margin-bottom: .15rem;
        font-size: .925rem;
        transition: background-color .15s ease, color .15s ease;
        white-space: nowrap;      /* el texto no se parte */
    }

    .sidebar-sigct .sidebar-link:hover {
        background-color: #00193b;
        color: #ffffff;
    }

    .sidebar-sigct .sidebar-link.active {
        background-color: #063d90;
        color: #ffffff;
        font-weight: 600;
    }

    .sidebar-sigct .sidebar-heading {
        color: #64748b;
        font-size: .7rem;
        text-transform: uppercase;
        letter-spacing: .05em;
        font-weight: 700;
        padding: .9rem .75rem .35rem;
    }
</style>