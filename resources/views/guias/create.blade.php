@extends('layouts.app')

@section('title','Nueva guía')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">
            <i class="bi bi-journal-plus me-2"></i>Nueva guía
        </h2>
        <a href="{{ route('guias.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Volver
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <i class="bi bi-exclamation-triangle me-2"></i>Revisa los siguientes datos:
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="{{ route('guias.store') }}" method="POST" enctype="multipart/form-data" class="row g-3">
                @csrf

                <div class="col-md-8">
                    <label class="form-label">Título <span class="text-danger">*</span></label>
                    <input type="text" name="titulo" maxlength="150" required
                           value="{{ old('titulo') }}"
                           class="form-control @error('titulo') is-invalid @enderror"
                           placeholder="Ej. Cómo facturar a OXXO">
                    @error('titulo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Categoría <span class="text-danger">*</span></label>
                    <select name="categoria" class="form-select @error('categoria') is-invalid @enderror" required>
                        <option value="">-- Selecciona --</option>
                        @foreach(\App\Models\Guia::CATEGORIAS as $cat)
                            <option value="{{ $cat }}" @selected(old('categoria') == $cat)>
                                {{ $cat }}
                            </option>
                        @endforeach
                    </select>
                    @error('categoria')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-12">
                    <label class="form-label">Descripción</label>
                    <textarea name="descripcion" rows="3" maxlength="1000"
                              class="form-control @error('descripcion') is-invalid @enderror"
                              placeholder="Breve explicación de para qué sirve esta guía...">{{ old('descripcion') }}</textarea>
                    @error('descripcion')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Archivo <span class="text-danger">*</span></label>
                    <input type="file" name="archivo" accept=".pdf,.jpg,.jpeg,.png,.webp" required
                           class="form-control @error('archivo') is-invalid @enderror">
                    <div class="form-text">PDF o imagen, máximo 10 MB. Se abrirá en el navegador sin descargar.</div>
                    @error('archivo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-upload me-1"></i>Subir guía
                    </button>
                    <a href="{{ route('guias.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                </div>

            </form>
        </div>
    </div>

</div>
@endsection