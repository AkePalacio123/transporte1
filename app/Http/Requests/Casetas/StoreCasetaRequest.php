<?php

namespace App\Http\Requests\Casetas;

use Illuminate\Foundation\Http\FormRequest;

class StoreCasetaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre'        => ['required', 'string', 'max:120'],
            'costo'         => ['required', 'numeric', 'min:0', 'max:99999.99'],
            'fecha'         => ['nullable', 'date'],
            'folio_ticket'  => ['nullable', 'string', 'max:50'],
            'observaciones' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function attributes(): array
    {
        return [
            'nombre'       => 'nombre de la caseta',
            'folio_ticket' => 'folio del ticket',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'nombre' => trim((string) $this->nombre),
        ]);
    }
}