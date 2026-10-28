<?php

namespace App\Http\Requests\Mantenimientos;

use Illuminate\Foundation\Http\FormRequest;

class StorePiezaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre'          => ['required', 'string', 'max:150'],
            'numero_parte'    => ['nullable', 'string', 'max:60'],
            'proveedor'       => ['nullable', 'string', 'max:120'],
            'cantidad'        => ['required', 'numeric', 'min:0.01', 'max:99999.99'],
            'precio_unitario' => ['required', 'numeric', 'min:0', 'max:9999999.99'],
        ];
    }

    public function attributes(): array
    {
        return [
            'nombre'          => 'nombre de la pieza',
            'numero_parte'    => 'número de parte',
            'precio_unitario' => 'precio unitario',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'nombre' => trim((string) $this->nombre),
        ]);
    }
}