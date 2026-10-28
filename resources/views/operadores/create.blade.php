@extends('layouts.app')

@section('title','Nuevo Operador')

@section('content')

<div class="container-fluid">

    <div class="mb-4">

        <h2 class="fw-bold mb-0">

            Nuevo Operador

        </h2>

        <small class="text-muted">

            Registrar un nuevo operador

        </small>

    </div>

    <form
        action="{{ route('operadores.store') }}"
        method="POST">

        @include('operadores.form')

    </form>

</div>

@endsection