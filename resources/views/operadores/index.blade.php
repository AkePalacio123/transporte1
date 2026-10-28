@extends('layouts.app')

@section('title', 'Operadores')

@section('content')

<div class="container-fluid">

    <!-- Encabezado -->
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2 class="fw-bold mb-1">
                Operadores
            </h2>

            <p class="text-muted mb-0">
                Administración de operadores registrados
            </p>
        </div>
        
        @if(auth()->user()->puedeEditar())
        <a href="{{ route('operadores.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>
            Nuevo Operador
        </a>
        @endif

    </div>

    @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            {{ session('success') }}

            <button class="btn-close" data-bs-dismiss="alert"></button>

        </div>

    @endif

    <div class="card shadow-sm border-0">

            <div class="card-header bg-white">

            <form action="{{ route('operadores.index') }}" method="GET">

                <div class="row">

                    <div class="col-md-5">

                        <input
                            type="text"
                            name="buscar"
                            value="{{ request('buscar') }}"
                            class="form-control"
                            placeholder="Buscar por nombre, teléfono o licencia...">

                    </div>

                    <div class="col-auto">

                        <button class="btn btn-primary">

                            <i class="bi bi-search"></i>

                            Buscar

                        </button>

                    </div>

                    @if(request('buscar'))

                        <div class="col-auto">

                            <a
                                href="{{ route('operadores.index') }}"
                                class="btn btn-secondary">

                                Limpiar

                            </a>

                        </div>

                    @endif

                </div>

            </form>

        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead class="table-light">

                        <tr>

                            <th style="width:70px">#</th>

                            <th>Operador</th>

                            <th>Licencia</th>

                            <th>Teléfono</th>

                            <th>Vence</th>

                            <th class="text-center">Estado</th>

                            <th class="text-center" style="width:180px">
                                Acciones
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                    @forelse($operadores as $operador)

                        <tr>

                            <td>

                                {{ $loop->iteration + ($operadores->currentPage()-1) * $operadores->perPage() }}

                            </td>

                            <td class="fw-semibold">

                                {{ $operador->nombre }}

                            </td>

                            <td>

                                {{ $operador->licencia }}

                            </td>

                            <td>

                                {{ $operador->telefono }}

                            </td>

                            <td>

                                {{ \Carbon\Carbon::parse($operador->vencimiento_licencia)->format('d/m/Y') }}

                            </td>

                            <td class="text-center">

                                @if($operador->estatus=="Activo")

                                    <span class="badge bg-success">

                                        Activo

                                    </span>

                                @elseif($operador->estatus=="Suspendido")

                                    <span class="badge bg-warning text-dark">

                                        Suspendido

                                    </span>

                                @else

                                    <span class="badge bg-danger">

                                        Inactivo

                                    </span>

                                @endif

                            </td>

                            <td>

                                <div class="d-flex justify-content-center gap-2 flex-nowrap">

                                    <a
                                        href="{{ route('operadores.show',$operador) }}"
                                        class="btn btn-info btn-sm"
                                        title="Ver">

                                        <i class="bi bi-eye"></i>

                                    </a>

                                    <a
                                        href="{{ route('operadores.edit',$operador) }}"
                                        class="btn btn-warning btn-sm"
                                        title="Editar">

                                        <i class="bi bi-pencil"></i>

                                    </a>

                                    <form
                                        action="{{ route('operadores.destroy',$operador) }}"
                                        method="POST">

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            class="btn btn-danger btn-sm"
                                            onclick="return confirm('¿Estás seguro de eliminar este operador? Esta acción no se puede deshacer.')">

                                            <i class="bi bi-trash"></i>

                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="7" class="text-center py-5">

                                <i class="bi bi-person-x display-5"></i>

                                <br><br>

                                No existen operadores registrados.

                            </td>

                        </tr>

                    @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

    <div class="mt-3">

        {{ $operadores->links() }}

    </div>

</div>

@endsection