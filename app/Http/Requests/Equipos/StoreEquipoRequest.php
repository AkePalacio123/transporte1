<?php

namespace App\Http\Requests\Equipos;

use Illuminate\Foundation\Http\FormRequest;

class StoreEquipoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'serie'               => ['required', 'string', 'max:50', 'unique:equipos,serie'],
            'placa'               => ['required', 'string', 'max:20', 'unique:equipos,placa'],
            'marca'               => ['required', 'string', 'max:60'],
            'modelo'              => ['required', 'string', 'max:60'],
            'anio'                => ['required', 'integer', 'min:1980', 'max:' . (date('Y') + 1)],
            'tarjeta_circulacion' => ['nullable', 'string', 'max:60'],
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