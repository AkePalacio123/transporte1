@extends('layouts.app')
@section('title','Editar asignación')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0"><i class="bi bi-pencil-square me-2"></i>Editar asignación #{{ $asignacion->id }}</h2>
        <a href="{{ route('asignaciones.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Volver
        </a>
    </div>
    <form action="{{ route('asignaciones.update',$asignacion) }}" method="POST">
        @csrf
        @method('PUT')
        @include('asignaciones._form')
        <div class="d-flex gap-2 mb-4">
            <button class="btn btn-primary"><i class="bi bi-check-circle me-1"></i>Actualizar</button>
            <a href="{{ route('asignaciones.index') }}" class="btn btn-light">Cancelar</a>
        </div>
    </form>
</div>
@endsection