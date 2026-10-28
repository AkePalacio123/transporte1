<?php

namespace App\Http\Requests\Tractos;

use Illuminate\Foundation\Http\FormRequest;

class StoreTractoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'serie'               => ['required', 'string', 'max:50', 'unique:tractos,serie'],
            'placa'               => ['required', 'string', 'max:20', 'unique:tractos,placa'],
            'marca'               => ['required', 'string', 'max:60'],
            'modelo'              => ['required', 'string', 'max:60'],
            'anio'                => ['required', 'integer', 'min:1980', 'max:' . (date('Y') + 1)],
            'estatus'             => ['required', 'in:Activo,Inactivo,Mantenimiento,Baja'],
            'observaciones'       => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function attributes(): array
    {
        return [
            'serie'               => 'número de serie (VIN)',
            'anio'                => 'año',
        ];
    }

    public function messages(): array
    {
        return [
            'serie.unique'       => 'Ese número de serie ya está registrado.',
            'placa.unique'       => 'Esa placa ya está registrada.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'serie' => strtoupper(trim((string) $this->serie)),
            'placa' => strtoupper(trim((string) $this->placa)),
        ]);
    }
}