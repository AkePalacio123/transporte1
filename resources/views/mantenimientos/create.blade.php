@extends('layouts.app')
@section('title','Nuevo mantenimiento')
@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0"><i class="bi bi-plus-circle me-2"></i>Nuevo mantenimiento</h2>
        <a href="{{ route('mantenimientos.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Volver
        </a>
    </div>
    <form action="{{ route('mantenimientos.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @include('mantenimientos._form')
        <div class="d-flex gap-2 mb-4">
            <button class="btn btn-primary"><i class="bi bi-check-circle me-1"></i>Guardar mantenimiento</button>
            <a href="{{ route('mantenimientos.index') }}" class="btn btn-light">Cancelar</a>
        </div>
    </form>
</div>
@endsection