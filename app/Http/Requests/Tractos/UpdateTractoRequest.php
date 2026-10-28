<?php

namespace App\Http\Requests\Tractos;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTractoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'serie'               => ['required', 'string', 'max:50', Rule::unique('tractos', 'serie')->ignore($this->route('tracto'))],
            'placa'               => ['required', 'string', 'max:20', Rule::unique('tractos', 'placa')->ignore($this->route('tracto'))],
            'marca'               => ['required', 'string', 'max:60'],
            'modelo'              => ['required', 'string', 'max:60'],
            'anio'                => ['required', 'integer', 'min:1980', 'max:' . (date('Y') + 1)],
            'tarjeta_circulacion' => ['nullable', 'string', 'max:60'],
            'tarjeta_pdf'         => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
            'estatus'             => ['required', 'in:Activo,Inactivo,Mantenimiento,Baja'],
            'observaciones'       => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function attributes(): array
    {
        return [
            'serie'               => 'número de serie (VIN)',
            'anio'                => 'año',
            'tarjeta_circulacion' => 'tarjeta de circulación',
            'tarjeta_pdf'         => 'PDF de la tarjeta de circulación',
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