<?php

namespace App\Http\Requests\Combustible;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCargaCombustibleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'estacion'      => ['required', 'string', 'max:120'],
            'fecha'         => ['required', 'date'],
            'litros'        => ['required', 'numeric', 'min:0.01', 'max:999999.99'],
            'precio_litro'  => ['nullable', 'numeric', 'min:0', 'max:999999.99'],
            'total'         => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'kilometraje'   => ['nullable', 'integer', 'min:0'],
            'folio_factura' => ['nullable', 'string', 'max:50'],
            'observaciones' => ['nullable', 'string', 'max:255'],
        ];
    }

    public function attributes(): array
    {
        return [
            'estacion'      => 'gasolinera',
            'precio_litro'  => 'precio por litro',
            'folio_factura' => 'folio de factura',
        ];
    }

    /**
     * Si no capturan el precio por litro, se calcula solo.
     */
    protected function prepareForValidation(): void
    {
        $litros = (float) $this->litros;
        $total  = (float) $this->total;

        $this->merge([
            'estacion'     => trim((string) $this->estacion),
            'precio_litro' => $this->filled('precio_litro')
                ? $this->precio_litro
                : ($litros > 0 ? round($total / $litros, 2) : 0),
        ]);
    }
}