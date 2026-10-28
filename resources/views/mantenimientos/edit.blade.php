@extends('layouts.app')
@section('title','Editar mantenimiento')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">
            <i class="bi bi-pencil-square me-2"></i>Editar mantenimiento #{{ $mantenimiento->id }}
        </h2>
        <a href="{{ route('mantenimientos.show',$mantenimiento) }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Volver
        </a>
    </div>
    <form action="{{ route('mantenimientos.update',$mantenimiento) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('mantenimientos._form')
        <div class="d-flex gap-2 mb-4">
            <button class="btn btn-primary"><i class="bi bi-check-circle me-1"></i>Actualizar</button>
            <a href="{{ route('mantenimientos.show',$mantenimiento) }}" class="btn btn-light">Cancelar</a>
        </div>
    </form>
</div>
@endsection