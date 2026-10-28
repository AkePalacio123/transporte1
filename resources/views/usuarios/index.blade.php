@extends('layouts.app')
@section('title','Usuarios')
@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0"><i class="bi bi-people-fill me-2"></i>Usuarios del sistema</h2>
        
        @if(auth()->user()->puedeEditar())
        <a href="{{ route('usuarios.create') }}" class="btn btn-primary">
            <i class="bi bi-person-plus me-2"></i>Nuevo usuario
        </a>
        @endif
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">

            <form method="GET" class="row g-2 mb-3">
                <div class="col-md-4">
                    <input type="text" name="buscar" value="{{ $buscar }}" class="form-control"
                           placeholder="Buscar por nombre o correo...">
                </div>
                <div class="col-auto">
                    <button class="btn btn-primary"><i class="bi bi-search"></i></button>
                </div>
                @if($buscar)
                    <div class="col-auto">
                        <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary">Limpiar</a>
                    </div>
                @endif
            </form>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Correo</th>
                            <th>Rol</th>
                            <th>Estado</th>
                            <th width="130">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($usuarios as $usuario)
                        <tr>
                            <td class="text-muted">
                                {{ $usuarios->firstItem() + $loop->index }}
                            </td>
                            <td class="fw-semibold">
                                {{ $usuario->name }}
                                @if($usuario->id === auth()->id())
                                    <span class="badge bg-info text-dark ms-1">Tú</span>
                                @endif
                            </td>
                            <td>{{ $usuario->email }}</td>
                            <td>
                                @php
                                    $colorRol = [
                                        'Administrador' => 'danger',
                                        'Capturista'    => 'primary',
                                        'Consulta'      => 'secondary',
                                    ];
                                @endphp
                                <span class="badge bg-{{ $colorRol[$usuario->rol] ?? 'secondary' }}">
                                    {{ $usuario->rol }}
                                </span>
                            </td>
                            <td>
                                @if($usuario->activo)
                                    <span class="badge bg-success">Activo</span>
                                @else
                                    <span class="badge bg-secondary">Inactivo</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('usuarios.edit',$usuario) }}"
                                       class="btn btn-warning btn-sm text-white" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    @if($usuario->id !== auth()->id())
                                        <form action="{{ route('usuarios.destroy',$usuario) }}" method="POST"
                                              onsubmit="return confirm('¿Eliminar al usuario {{ $usuario->name }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm" title="Eliminar">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                No hay usuarios registrados.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            {{ $usuarios->links() }}

        </div>
    </div>

</div>
@endsection