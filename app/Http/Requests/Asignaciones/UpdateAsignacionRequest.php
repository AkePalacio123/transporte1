<?php

namespace App\Http\Requests\Asignaciones;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAsignacionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'operador_id'   => ['required', 'exists:operadores,id'],
            'tracto_id'     => ['required', 'exists:tractos,id'],
            'equipo_id'     => ['nullable', 'exists:equipos,id'],
            'fecha_inicio'  => ['required', 'date'],
            'fecha_fin'     => ['nullable', 'date', 'after_or_equal:fecha_inicio'],
            'activa'        => ['boolean'],
            'observaciones' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function attributes(): array
    {
        return [
            'operador_id'  => 'operador',
            'tracto_id'    => 'tracto',
            'equipo_id'    => 'equipo',
            'fecha_inicio' => 'fecha de inicio',
            'fecha_fin'    => 'fecha de fin',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'activa' => $this->boolean('activa'),
        ]);
    }
}