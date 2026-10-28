<?php

namespace App\Http\Requests\Empresas;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEmpresaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'nombre'=>'required|max:100|unique:empresas,nombre,'.request()->empresa->id,

            'rfc'=>'nullable|max:20',

            'contacto'=>'nullable|max:100',

            'telefono'=>'nullable|max:20',

            'correo'=>'nullable|email|max:100',

            'estatus'=>'required',

            'observaciones'=>'nullable'

        ];
    }
}