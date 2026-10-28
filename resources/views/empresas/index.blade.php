@extends('layouts.app')

@section('title','Empresas')

@section('content')

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <h2 class="fw-bold mb-0">
            <i class="bi bi-building me-2"></i>
            Empresas
        </h2>
        
        @if(auth()->user()->puedeEditar())
        <a href="{{ route('empresas.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>
            Nueva Empresa
        </a>
        @endif

    </div>

    <div class="card shadow-sm border-0">

        <div class="card-body">

            <form method="GET" class="row g-2 mb-3">

                <div class="col-md-4">
                    <input
                        type="text"
                        name="buscar"
                        value="{{ $buscar }}"
                        class="form-control"
                        placeholder="Buscar empresa...">
                </div>

                <div class="col-auto">
                    <button class="btn btn-primary">
                        <i class="bi bi-search"></i>
                    </button>
                </div>

                @if($buscar)
                    <div class="col-auto">
                        <a href="{{ route('empresas.index') }}" class="btn btn-outline-secondary">
                            Limpiar
                        </a>
                    </div>
                @endif

            </form>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="bi bi-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>RFC</th>
                            <th>Contacto</th>
                            <th>Teléfono</th>
                            <th class="text-center">Viajes</th>
                            <th>Estatus</th>
                            <th width="180">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($empresas as $empresa)

                        <tr>

                            <td>{{ $empresa->id }}</td>

                            <td class="fw-semibold">{{ $empresa->nombre }}</td>

                            <td>{{ $empresa->rfc }}</td>

                            <td>{{ $empresa->contacto }}</td>

                            <td>{{ $empresa->telefono }}</td>

                            <td class="text-center">
                                <span class="badge bg-secondary">
                                    {{ $empresa->viajes_count }}
                                </span>
                            </td>

                            <td>
                                @if($empresa->estatus == 'Activa')
                                    <span class="badge bg-success">Activa</span>
                                @else
                                    <span class="badge bg-danger">Inactiva</span>
                                @endif
                            </td>

                            <td>

                                <div class="d-flex flex-wrap gap-1">

                                    <a
                                        href="{{ route('empresas.show',$empresa) }}"
                                        class="btn btn-info btn-sm text-white"
                                        title="Ver detalle">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    <a
                                        href="{{ route('empresas.edit',$empresa) }}"
                                        class="btn btn-warning btn-sm text-white"
                                        title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    @if($empresa->viajes_count > 0)

                                        <button
                                            class="btn btn-danger btn-sm"
                                            disabled
                                            title="No se puede eliminar: tiene viajes registrados">
                                            <i class="bi bi-trash"></i>
                                        </button>

                                    @else

                                        <form
                                            action="{{ route('empresas.destroy',$empresa) }}"
                                            method="POST"
                                            onsubmit="return confirm('¿Eliminar la empresa {{ $empresa->nombre }}?')">

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
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="bi bi-inbox d-block fs-3 mb-2"></i>
                                No hay empresas registradas.
                            </td>
                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            {{ $empresas->links() }}

        </div>

    </div>

</div>

@endsection