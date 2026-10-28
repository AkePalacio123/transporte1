<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'rol',
        'activo',
        'modulos_permitidos',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at'  => 'datetime',
        'activo'             => 'boolean',
        'modulos_permitidos' => 'array',
    ];

    /* ---------------- Roles ---------------- */

    public const ROLES = ['Administrador', 'Capturista', 'Consulta'];

    public function esAdministrador(): bool
    {
        return $this->rol === 'Administrador';
    }

    public function puedeEditar(): bool
    {
        // Administrador y Capturista pueden crear/editar.
        return in_array($this->rol, ['Administrador', 'Capturista'], true);
    }

    public function tieneRol(string ...$roles): bool
    {
        return in_array($this->rol, $roles, true);
    }

    /* ---------------- Módulos ---------------- */

    /**
     * Catálogo de módulos que se pueden controlar por usuario.
     * La clave es el identificador interno; el valor, el nombre visible.
     */
    public const MODULOS = [
        'viajes'         => 'Viajes',
        'casetas'        => 'Casetas',
        'mantenimientos' => 'Mantenimiento',
        'asignaciones'   => 'Asignaciones',
        'empresas'       => 'Empresas',
        'operadores'     => 'Operadores',
        'tractos'        => 'Tractos',
        'equipos'        => 'Equipos',
        'guias'          => 'Guías',
        'alertas'        => 'Alertas',
    ];

    /**
     * ¿El usuario puede ver un módulo dado?
     *
     * Reglas:
     *  - El Administrador ve todo, siempre.
     *  - Si no tiene módulos marcados (null o vacío), ve todo
     *    (así los usuarios existentes no se ven afectados).
     *  - Si tiene módulos marcados, solo ve los de su lista.
     */
    public function puedeVerModulo(string $modulo): bool
    {
        if ($this->esAdministrador()) {
            return true;
        }

        $permitidos = $this->modulos_permitidos;

        if (empty($permitidos)) {
            return true;
        }

        return in_array($modulo, $permitidos, true);
    }
}