<?php

namespace App\Http\Requests\Usuarios;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->esAdministrador();
    }

    public function rules(): array
    {
        return [
            'name'     => ['required', 'string', 'max:100'],
            'email'    => ['required', 'email', 'max:150', 'unique:users,email'],
            'rol'      => ['required', Rule::in(User::ROLES)],
            'activo'   => ['boolean'],
            'password' => ['required', 'confirmed', Password::min(6)],
            'modulos_permitidos'   => ['nullable', 'array'],
            'modulos_permitidos.*' => ['string'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name'  => 'nombre',
            'email' => 'correo',
            'rol'   => 'rol',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['activo' => $this->boolean('activo')]);
    }
}