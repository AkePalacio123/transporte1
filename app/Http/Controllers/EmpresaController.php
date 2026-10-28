<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use Illuminate\Http\Request;
use App\Http\Requests\Empresas\StoreEmpresaRequest;
use App\Http\Requests\Empresas\UpdateEmpresaRequest;

class EmpresaController extends Controller
{
    /**
     * Mostrar listado.
     */
    public function index(Request $request)
    {
        $buscar = $request->buscar;

        $empresas = Empresa::when($buscar,function($query) use($buscar){

                $query->where('nombre','like',"%{$buscar}%")
                    ->orWhere('rfc','like',"%{$buscar}%")
                    ->orWhere('contacto','like',"%{$buscar}%");

            })
            ->withCount('viajes')
            ->orderBy('id')
            ->paginate(10)
            ->withQueryString();

        return view('empresas.index',compact(
            'empresas',
            'buscar'
        ));
    }

    /**
     * Mostrar formulario.
     */
    public function create()
    {
        return view('empresas.create');
    }

    /**
     * Guardar.
     */
    public function store(StoreEmpresaRequest $request)
    {
        Empresa::create($request->validated());

        return redirect()
            ->route('empresas.index')
            ->with(
                'success',
                'Empresa registrada correctamente.'
            );
    }

    /**
     * Mostrar detalle.
     */
    public function show(Empresa $empresa)
    {
        return view(
            'empresas.show',
            compact('empresa')
        );
    }

    /**
     * Mostrar edición.
     */
    public function edit(Empresa $empresa)
    {
        return view(
            'empresas.edit',
            compact('empresa')
        );
    }

    /**
     * Actualizar.
     */
    public function update(
        UpdateEmpresaRequest $request,
        Empresa $empresa
    )
    {

        $empresa->update(
            $request->validated()
        );

        return redirect()
            ->route('empresas.index')
            ->with(
                'success',
                'Empresa actualizada correctamente.'
            );

    }

    /**
 * Eliminar.
 */
    public function destroy(Empresa $empresa)
    {
        // Protege el historial: no se borra una empresa con viajes.
        if ($empresa->viajes()->exists()) {
            return redirect()
                ->route('empresas.index')
                ->with(
                    'error',
                    'No se puede eliminar esta empresa porque tiene viajes registrados.'
                );
        }

        $empresa->delete();

        return redirect()
            ->route('empresas.index')
            ->with(
                'success',
                'Empresa eliminada correctamente.'
            );
    }
}