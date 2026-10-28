@extends('layouts.app')

@section('title','Empresa')

@section('content')

<div class="container">

    <div class="card shadow-sm border-0">

        <div class="card-header bg-white">

            <h4 class="mb-0">

                <i class="bi bi-building me-2"></i>

                {{ $empresa->nombre }}

            </h4>

        </div>

        <div class="card-body">

            <div class="row">

                <div class="col-md-6 mb-3">

                    <strong>RFC</strong>

                    <br>

                    {{ $empresa->rfc ?: 'No registrado' }}

                </div>

                <div class="col-md-6 mb-3">

                    <strong>Contacto</strong>

                    <br>

                    {{ $empresa->contacto ?: 'No registrado' }}

                </div>

                <div class="col-md-6 mb-3">

                    <strong>Teléfono</strong>

                    <br>

                    {{ $empresa->telefono ?: 'No registrado' }}

                </div>

                <div class="col-md-6 mb-3">

                    <strong>Correo</strong>

                    <br>

                    {{ $empresa->correo ?: 'No registrado' }}

                </div>

                <div class="col-md-6 mb-3">

                    <strong>Estatus</strong>

                    <br>

                    @if($empresa->estatus=='Activa')

                        <span class="badge bg-success">

                            Activa

                        </span>

                    @else

                        <span class="badge bg-danger">

                            Inactiva

                        </span>

                    @endif

                </div>

                <div class="col-md-6 mb-3">

                    <strong>Fecha de registro</strong>

                    <br>

                    {{ $empresa->created_at->format('d/m/Y H:i') }}

                </div>

                <div class="col-12">

                    <strong>Observaciones</strong>

                    <hr>

                    {{ $empresa->observaciones ?: 'Sin observaciones.' }}

                </div>

            </div>

        </div>

        <div class="card-footer bg-white">

            <a
                href="{{ route('empresas.edit',$empresa) }}"
                class="btn btn-warning">

                <i class="bi bi-pencil me-2"></i>

                Editar

            </a>

            <a
                href="{{ route('empresas.index') }}"
                class="btn btn-secondary">

                <i class="bi bi-arrow-left me-2"></i>

                Regresar

            </a>

        </div>

    </div>

</div>

@endsection