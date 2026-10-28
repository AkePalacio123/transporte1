@extends('layouts.app')

@section('title','Editar viaje')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">
            <i class="bi bi-pencil-square me-2"></i>
            Editar viaje {{ $viaje->identificador }}
        </h2>
        <a href="{{ route('viajes.show',$viaje) }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Volver
        </a>
    </div>

    <form action="{{ route('viajes.update',$viaje) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        @include('viajes._form')

        <div class="d-flex gap-2 mb-4">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-circle me-1"></i>Actualizar viaje
            </button>
            <a href="{{ route('viajes.show',$viaje) }}" class="btn btn-light">Cancelar</a>
        </div>

    </form>

</div>
@endsection