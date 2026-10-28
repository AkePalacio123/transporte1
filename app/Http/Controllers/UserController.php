<?php

namespace App\Http\Controllers;

use App\Http\Requests\Usuarios\StoreUserRequest;
use App\Http\Requests\Usuarios\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $buscar = $request->buscar;

        $usuarios = User::when($buscar, function ($q) use ($buscar) {
                $q->where('name', 'like', "%{$buscar}%")
                  ->orWhere('email', 'like', "%{$buscar}%");
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('usuarios.index', compact('usuarios', 'buscar'));
    }

    public function create()
    {
        return view('usuarios.create', ['roles' => User::ROLES]);
    }

    public function store(StoreUserRequest $request)
    {
        $datos = $request->validated();
        $datos['password'] = Hash::make($datos['password']);

        User::create($datos);

        return redirect()
            ->route('usuarios.index')
            ->with('success', 'Usuario creado correctamente.');
    }

    public function edit(User $usuario)
    {
        return view('usuarios.edit', [
            'usuario' => $usuario,
            'roles'   => User::ROLES,
        ]);
    }

    public function update(UpdateUserRequest $request, User $usuario)
    {
        $datos = $request->validated();

        // Solo actualizar la contraseña si se escribió una nueva.
        if (! empty($datos['password'])) {
            $datos['password'] = Hash::make($datos['password']);
        } else {
            unset($datos['password']);
        }

        // Evitar que el único administrador se quite el rol a sí mismo.
        if ($usuario->id === $request->user()->id
            && $usuario->esAdministrador()
            && $datos['rol'] !== 'Administrador') {

            return back()->with('error', 'No puedes quitarte el rol de Administrador a ti mismo.');
        }

        $usuario->update($datos);

        return redirect()
            ->route('usuarios.index')
            ->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy(Request $request, User $usuario)
    {
        // Protección: no borrarse a uno mismo.
        if ($usuario->id === $request->user()->id) {
            return redirect()
                ->route('usuarios.index')
                ->with('error', 'No puedes eliminar tu propia cuenta.');
        }

        // Protección: no borrar al último administrador.
        if ($usuario->esAdministrador() && User::where('rol', 'Administrador')->count() <= 1) {
            return redirect()
                ->route('usuarios.index')
                ->with('error', 'No puedes eliminar al único administrador del sistema.');
        }

        $usuario->delete();

        return redirect()
            ->route('usuarios.index')
            ->with('success', 'Usuario eliminado correctamente.');
    }
}