<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OperadorController;
use App\Http\Controllers\TractoController;
use App\Http\Controllers\EquipoController;
use App\Http\Controllers\ViajeController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\CasetaController;
use App\Http\Controllers\CargaCombustibleController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MantenimientoController;
use App\Http\Controllers\PiezaMantenimientoController;
use App\Http\Controllers\AsignacionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\DocumentoOperadorController;
use App\Http\Controllers\DocumentoTractoController;
use App\Http\Controllers\DocumentoEquipoController;
use App\Http\Controllers\GuiaController;
use App\Http\Controllers\AlertaDocumentoController;
use App\Http\Controllers\AlertaMantenimientoController;
use App\Http\Controllers\ReparacionController;
use App\Http\Controllers\CalificacionOperadorController;

/*
|--------------------------------------------------------------------------
| Rutas del sistema — TODAS requieren iniciar sesión (middleware auth)
|--------------------------------------------------------------------------
|
| Nota sobre el orden: las rutas de ESCRITURA (que incluyen 'create')
| se declaran ANTES que las rutas de lectura con parámetro ({modelo}).
| Así 'create' nunca se confunde con un {id}.
|
| Permisos por módulo: las rutas de lectura de cada módulo se agrupan
| con el middleware 'modulo:CLAVE'. El Administrador y los usuarios sin
| módulos marcados pasan siempre (ver modelo User::puedeVerModulo).
|
*/

Route::middleware(['auth'])->group(function () {

    /* ---------- Dashboard (todos) ---------- */
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');


    /* ==============================================================
     | ESCRITURA — solo Administrador y Capturista
     | create, store, edit, update, destroy
     | ============================================================*/

    Route::middleware('rol:Administrador,Capturista')->group(function () {

        /* ---------- Catálogos ---------- */
        Route::middleware('modulo:operadores')->group(function () {
            Route::resource('operadores', OperadorController::class)
                ->parameters(['operadores' => 'operador'])
                ->only(['create', 'store', 'edit', 'update', 'destroy']);
        });

        Route::middleware('modulo:tractos')->group(function () {
            Route::resource('tractos', TractoController::class)
                ->parameters(['tractos' => 'tracto'])
                ->only(['create', 'store', 'edit', 'update', 'destroy']);
        });

        Route::middleware('modulo:equipos')->group(function () {
            Route::resource('equipos', EquipoController::class)
                ->parameters(['equipos' => 'equipo'])
                ->only(['create', 'store', 'edit', 'update', 'destroy']);
        });

        Route::middleware('modulo:empresas')->group(function () {
            Route::resource('empresas', EmpresaController::class)
                ->only(['create', 'store', 'edit', 'update', 'destroy']);
        });

        /* ---------- Viajes ---------- */
        Route::middleware('modulo:viajes')->group(function () {
            Route::resource('viajes', ViajeController::class)
                ->parameters(['viajes' => 'viaje'])
                ->only(['create', 'store', 'edit', 'update', 'destroy']);

            Route::resource('viajes.casetas', CasetaController::class)
                ->shallow()
                ->only(['store', 'edit', 'update', 'destroy'])
                ->parameters(['viajes' => 'viaje']);

            Route::resource('viajes.combustible', CargaCombustibleController::class)
                ->shallow()
                ->only(['store', 'edit', 'update', 'destroy'])
                ->parameters(['viajes' => 'viaje', 'combustible' => 'combustible']);

            Route::post('viajes/{viaje}/reparaciones',
                [ReparacionController::class, 'store'])
                ->name('viajes.reparaciones.store');

            Route::delete('reparaciones/{reparacion}',
                [ReparacionController::class, 'destroy'])
                ->name('reparaciones.destroy');
        });

        

        /* ---------- Mantenimientos ---------- */
        Route::middleware('modulo:mantenimientos')->group(function () {
            Route::resource('mantenimientos', MantenimientoController::class)
                ->parameters(['mantenimientos' => 'mantenimiento'])
                ->only(['create', 'store', 'edit', 'update', 'destroy']);

            Route::resource('mantenimientos.piezas', PiezaMantenimientoController::class)
                ->shallow()
                ->only(['store', 'destroy'])
                ->parameters(['mantenimientos' => 'mantenimiento', 'piezas' => 'pieza']);
        });

        /* ---------- Asignaciones ---------- */
        Route::middleware('modulo:asignaciones')->group(function () {
            Route::resource('asignaciones', AsignacionController::class)
                ->parameters(['asignaciones' => 'asignacion'])
                ->only(['create', 'store', 'edit', 'update', 'destroy']);
        });

        /* ---------- Documentos de operadores ---------- */
        Route::middleware('modulo:operadores')->group(function () {
            Route::post('operadores/{operador}/documentos',
                [DocumentoOperadorController::class, 'store'])
                ->name('operadores.documentos.store');

            Route::delete('documentos-operador/{documento}',
                [DocumentoOperadorController::class, 'destroy'])
                ->name('operadores.documentos.destroy');
        });

        /* ---------- Documentos de tractos ---------- */
        Route::middleware('modulo:tractos')->group(function () {
            Route::post('tractos/{tracto}/documentos',
                [DocumentoTractoController::class, 'store'])
                ->name('tractos.documentos.store');
            Route::delete('documentos-tracto/{documento}',
                [DocumentoTractoController::class, 'destroy'])
                ->name('tractos.documentos.destroy');
        });

        /* ---------- Documentos de equipos ---------- */
        Route::middleware('modulo:equipos')->group(function () {
            Route::post('equipos/{equipo}/documentos',
                [DocumentoEquipoController::class, 'store'])
                ->name('equipos.documentos.store');
            Route::delete('documentos-equipo/{documento}',
                [DocumentoEquipoController::class, 'destroy'])
                ->name('equipos.documentos.destroy');
        });

        /* ---------- Guías (escritura) ---------- */
        Route::middleware('modulo:guias')->group(function () {
            Route::get('guias/create', [GuiaController::class, 'create'])->name('guias.create');
            Route::post('guias', [GuiaController::class, 'store'])->name('guias.store');
            Route::delete('guias/{guia}', [GuiaController::class, 'destroy'])->name('guias.destroy');
        });
    });


    /* ==============================================================
     | LECTURA — todos los roles (incluye Consulta)
     | index y show. Cada módulo protegido por su middleware 'modulo:'.
     | ============================================================*/

    Route::middleware('modulo:operadores')->group(function () {
        Route::get('operadores', [OperadorController::class, 'index'])->name('operadores.index');
        Route::get('operadores/{operador}', [OperadorController::class, 'show'])->name('operadores.show');
    });

    Route::get('calificacion-operadores',
        [CalificacionOperadorController::class, 'index'])
        ->name('calificacion.operadores');
        
    Route::middleware('modulo:tractos')->group(function () {
        Route::get('tractos', [TractoController::class, 'index'])->name('tractos.index');
        Route::get('tractos/{tracto}', [TractoController::class, 'show'])->name('tractos.show');
    });

    Route::middleware('modulo:equipos')->group(function () {
        Route::get('equipos', [EquipoController::class, 'index'])->name('equipos.index');
        Route::get('equipos/{equipo}', [EquipoController::class, 'show'])->name('equipos.show');
    });

    Route::middleware('modulo:empresas')->group(function () {
        Route::get('empresas', [EmpresaController::class, 'index'])->name('empresas.index');
        Route::get('empresas/{empresa}', [EmpresaController::class, 'show'])->name('empresas.show');
    });

    Route::middleware('modulo:viajes')->group(function () {
        Route::get('viajes', [ViajeController::class, 'index'])->name('viajes.index');
        Route::get('viajes/{viaje}', [ViajeController::class, 'show'])->name('viajes.show');
    });

    Route::middleware('modulo:mantenimientos')->group(function () {
        Route::get('mantenimientos', [MantenimientoController::class, 'index'])->name('mantenimientos.index');
        Route::get('mantenimientos/{mantenimiento}', [MantenimientoController::class, 'show'])->name('mantenimientos.show');
    });

    Route::middleware('modulo:asignaciones')->group(function () {
        Route::get('asignaciones', [AsignacionController::class, 'index'])->name('asignaciones.index');
        Route::get('asignaciones/{asignacion}', [AsignacionController::class, 'show'])->name('asignaciones.show');
    });

    // API interna: autocompletar unidad del operador (parte del módulo viajes)
    Route::middleware('modulo:viajes')->group(function () {
        Route::get('api/operador/{operador}/asignacion',
            [ViajeController::class, 'asignacionDeOperador'])
            ->name('operador.asignacion');
    });

    Route::middleware('modulo:guias')->group(function () {
        Route::get('guias', [GuiaController::class, 'index'])->name('guias.index');
    });

    Route::middleware('modulo:casetas')->group(function () {
        Route::get('casetas', [CasetaController::class, 'index'])->name('casetas.index');
        Route::get('reportes/casetas', [ReporteController::class, 'casetas'])->name('reportes.casetas');
    });

    Route::middleware('modulo:alertas')->group(function () {
        Route::get('alertas-documentos', [AlertaDocumentoController::class, 'index'])
            ->name('alertas.documentos');

        Route::get('alertas-mantenimiento', [AlertaMantenimientoController::class, 'index'])
            ->name('alertas.mantenimiento');
    });


    /* ==============================================================
     | REPORTES — protegidos por su módulo correspondiente
     | ============================================================*/

    Route::middleware('modulo:viajes')->group(function () {
        Route::get('reportes/viajes', [ReporteController::class, 'viajes'])->name('reportes.viajes');
    });

    Route::middleware('modulo:mantenimientos')->group(function () {
        Route::get('reportes/mantenimientos', [ReporteController::class, 'mantenimientos'])->name('reportes.mantenimientos');
    });


    /* ==============================================================
     | USUARIOS — solo Administrador
     | ============================================================*/

    Route::middleware('rol:Administrador')->group(function () {
        Route::resource('usuarios', UserController::class)
            ->parameters(['usuarios' => 'usuario'])
            ->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
    });

});

/*
|--------------------------------------------------------------------------
| Rutas de autenticación (Breeze)
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';