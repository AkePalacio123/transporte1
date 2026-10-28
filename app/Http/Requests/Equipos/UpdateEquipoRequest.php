<?php

namespace App\Http\Requests\Equipos;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEquipoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'serie'               => ['required', 'string', 'max:50', Rule::unique('equipos', 'serie')->ignore($this->route('equipo'))],
            'placa'               => ['required', 'string', 'max:20', Rule::unique('equipos', 'placa')->ignore($this->route('equipo'))],
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