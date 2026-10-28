@extends('layouts.app')

@section('title','Editar Operador')

@section('content')

<div class="container-fluid">

    <div class="mb-4">

        <h2 class="fw-bold mb-0">

            Editar Operador

        </h2>

    </div>

    <form
        action="{{ route('operadores.update',$operador) }}"
        method="POST">

        @csrf

        @method('PUT')

        @include('operadores.form')

    </form>

</div>

@endsection