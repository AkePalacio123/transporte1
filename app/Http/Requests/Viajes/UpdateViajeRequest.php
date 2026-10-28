<?php

namespace App\Http\Requests\Viajes;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateViajeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Reglas de validacion para actualizar un viaje existente.
     */
    public function rules(): array
    {
        return [
            // --- Identificacion ---
            'empresa_id'   => ['required', 'exists:empresas,id'],
            'folio'        => ['nullable', 'string', 'max:30', Rule::unique('viajes', 'folio')->ignore($this->route('viaje'))],
            'cataporte'    => ['required', 'string', 'max:30'],
            'tipo_viaje'   => ['required', 'in:Normal,Retorno'],

            // --- Asignacion ---
            'operador_id'  => ['required', 'exists:operadores,id'],
            'tracto_id'    => ['required', 'exists:tractos,id'],
            'equipo_id'    => ['nullable', 'exists:equipos,id'],

            // --- Ruta ---
            'origen'       => ['required', 'string', 'max:150'],
            'destino'      => ['required', 'string', 'max:150'],
            'kilometros' => ['nullable', 'integer', 'min:0', 'max:100000'],

            // --- Fechas ---
            'fecha_viaje'   => ['required', 'date'],
            'fecha_captura' => ['required', 'date'],

            // --- Pago ---
            'pago'         => ['required', 'numeric', 'min:0', 'max:9999999.99'],
            'estatus'      => ['required', 'in:Pendiente,En ruta,Finalizado,Cancelado'],
            'estatus_pago' => ['required', 'in:Pendiente,Pagado'],
            'fecha_pago'   => [
                'nullable',
                'required_if:estatus_pago,Pagado',
                'date',
                'after_or_equal:fecha_viaje',
            ],
            'fecha_limite_pago' => ['nullable', 'date'],

            // --- Bitacora ---
            'estatus_bitacora' => ['required', 'in:No entregada,Entregada'],
            'entregado_a' => [
                'nullable',
                'required_if:estatus_bitacora,Entregada',
                'string',
                'max:100',
            ],
            'fecha_entrega_bitacora' => [
                'nullable',
                'required_if:estatus_bitacora,Entregada',
                'date',
                'after_or_equal:fecha_viaje',
            ],

            // --- Documento y notas ---
            'archivo_bitacora' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
            'observaciones'    => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * Nombres amigables para los mensajes de error.
     */
    public function attributes(): array
    {
        return [
            'empresa_id'             => 'empresa',
            'operador_id'            => 'operador',
            'tracto_id'              => 'tracto',
            'equipo_id'              => 'equipo',
            'cataporte'              => 'carta porte',
            'tipo_viaje'             => 'tipo de viaje',
            'fecha_viaje'            => 'fecha del viaje',
            'fecha_captura'          => 'fecha de captura',
            'fecha_pago'             => 'fecha de pago',
            'fecha_limite_pago'      => 'fecha limite de pago',
            'estatus_pago'           => 'estatus de pago',
            'estatus_bitacora'       => 'estatus de bitacora',
            'entregado_a'            => 'entregado a',
            'fecha_entrega_bitacora' => 'fecha de entrega de bitacora',
            'archivo_bitacora'       => 'archivo PDF de la bitacora',
        ];
    }

    /**
     * Mensajes personalizados en espanol.
     */
    public function messages(): array
    {
        return [
            'folio.unique' => 'Ese folio ya fue registrado en otro viaje.',
            'fecha_pago.required_if' => 'Si el viaje esta pagado, debes indicar la fecha de pago.',
            'fecha_pago.after_or_equal' => 'La fecha de pago no puede ser anterior al viaje.',
            'entregado_a.required_if' => 'Indica a quien se entrego la bitacora.',
            'fecha_entrega_bitacora.required_if' => 'Indica la fecha en que se entrego la bitacora.',
            'archivo_bitacora.mimes' => 'La bitacora debe ser un archivo PDF.',
            'archivo_bitacora.max' => 'El PDF no debe pesar mas de 10 MB.',
        ];
    }

    /**
     * Limpia los datos antes de validarlos.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'folio'     => $this->filled('folio') ? trim($this->folio) : null,
            'cataporte' => trim((string) $this->cataporte),
            'origen'    => trim((string) $this->origen),
            'destino'   => trim((string) $this->destino),
        ]);
    }
}