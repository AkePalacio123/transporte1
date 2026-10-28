@extends('layouts.app')

@section('title','Editar Empresa')

@section('content')

<div class="container">

    <div class="mb-4">

        <h2 class="fw-bold">

            <i class="bi bi-pencil-square me-2"></i>

            Editar Empresa

        </h2>

    </div>

    <form
        action="{{ route('empresas.update',$empresa) }}"
        method="POST">

        @csrf

        @method('PUT')

        @include('empresas._form')

    </form>

</div>

@endsection