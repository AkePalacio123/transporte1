<?php

namespace App\Http\Requests\Mantenimientos;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMantenimientoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // La unidad llega como 'tracto-3' o 'equipo-5'.
            'unidad'          => ['required', 'string', 'regex:/^(tracto|equipo)-\d+$/'],

            'tipo'            => ['required', 'in:Preventivo,Correctivo'],
            'estatus'         => ['required', 'in:En taller,Finalizado,Cancelado'],

            'fecha_entrada'   => ['required', 'date'],
            'fecha_salida'    => ['nullable', 'date', 'after_or_equal:fecha_entrada'],

            'kilometraje'     => ['nullable', 'integer', 'min:0'],
            'taller'          => ['nullable', 'string', 'max:120'],

            'descripcion'     => ['required', 'string', 'max:2000'],
            'costo_mano_obra' => ['required', 'numeric', 'min:0', 'max:9999999.99'],

            'folio_factura'   => ['nullable', 'string', 'max:50'],
            'factura_pdf'     => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
            'observaciones'   => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function attributes(): array
    {
        return [
            'unidad'          => 'unidad',
            'fecha_entrada'   => 'fecha de entrada',
            'fecha_salida'    => 'fecha de salida',
            'costo_mano_obra' => 'costo de mano de obra',
            'folio_factura'   => 'folio de factura',
            'factura_pdf'     => 'PDF de la factura',
        ];
    }

    public function messages(): array
    {
        return [
            'unidad.required'  => 'Debes seleccionar el tracto o equipo atendido.',
            'unidad.regex'     => 'La unidad seleccionada no es válida.',
            'fecha_salida.after_or_equal' => 'La fecha de salida no puede ser anterior a la de entrada.',
            'factura_pdf.mimes' => 'La factura debe ser un archivo PDF.',
            'factura_pdf.max'   => 'El PDF no debe pesar más de 10 MB.',
        ];
    }

    /**
     * Si se marca como Finalizado, la fecha de salida es obligatoria.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if ($this->estatus === 'Finalizado' && blank($this->fecha_salida)) {
                $validator->errors()->add(
                    'fecha_salida',
                    'Si el mantenimiento está finalizado, indica la fecha de salida.'
                );
            }
        });
    }
}