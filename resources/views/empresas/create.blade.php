@extends('layouts.app')

@section('title','Nueva Empresa')

@section('content')

<div class="container">

    <div class="mb-4">

        <h2 class="fw-bold">

            <i class="bi bi-building me-2"></i>

            Registrar Empresa

        </h2>

    </div>

    <form
        action="{{ route('empresas.store') }}"
        method="POST">

        @include('empresas._form')

    </form>

</div>

@endsection