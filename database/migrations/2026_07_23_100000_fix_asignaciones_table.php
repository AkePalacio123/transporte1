<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Alinea la tabla asignaciones con el modelo:
     *  - fecha_asignacion -> fecha_inicio
     *  - estatus (texto) -> activa (booleano)
     *  - agrega observaciones
     *  - equipo_id pasa a ser opcional
     *  - cascade -> restrict para no borrar historial
     */
    public function up(): void
    {
        // 1. Renombrar fecha_asignacion -> fecha_inicio.
        DB::statement('ALTER TABLE `asignaciones` CHANGE `fecha_asignacion` `fecha_inicio` DATE NOT NULL');

        // 2. Agregar columnas nuevas.
        Schema::table('asignaciones', function (Blueprint $table) {
            if (! Schema::hasColumn('asignaciones', 'activa')) {
                $table->boolean('activa')->default(true)->after('fecha_fin');
            }
            if (! Schema::hasColumn('asignaciones', 'observaciones')) {
                $table->text('observaciones')->nullable()->after('activa');
            }
        });

        // 3. Pasar el valor de estatus (texto) a activa (booleano).
        DB::statement("UPDATE `asignaciones` SET `activa` = CASE WHEN `estatus` = 'Activa' THEN 1 ELSE 0 END");

        // 4. Eliminar la columna estatus vieja.
        if (Schema::hasColumn('asignaciones', 'estatus')) {
            Schema::table('asignaciones', function (Blueprint $table) {
                $table->dropColumn('estatus');
            });
        }

        // 5. equipo_id opcional (una asignación puede ser solo de tracto).
        DB::statement('ALTER TABLE `asignaciones` MODIFY `equipo_id` BIGINT UNSIGNED NULL');

        // 6. Cambiar los cascade por restrict para proteger el historial.
        Schema::table('asignaciones', function (Blueprint $table) {
            $table->dropForeign('asignaciones_operador_id_foreign');
            $table->dropForeign('asignaciones_tracto_id_foreign');
            $table->dropForeign('asignaciones_equipo_id_foreign');
        });

        Schema::table('asignaciones', function (Blueprint $table) {
            $table->foreign('operador_id')->references('id')->on('operadores')
                  ->cascadeOnUpdate()->restrictOnDelete();
            $table->foreign('tracto_id')->references('id')->on('tractos')
                  ->cascadeOnUpdate()->restrictOnDelete();
            $table->foreign('equipo_id')->references('id')->on('equipos')
                  ->cascadeOnUpdate()->restrictOnDelete();
        });

        // 7. Índices útiles para consultas de asignaciones activas.
        Schema::table('asignaciones', function (Blueprint $table) {
            $table->index(['operador_id', 'activa']);
            $table->index(['tracto_id', 'activa']);
        });
    }

    public function down(): void
    {
        Schema::table('asignaciones', function (Blueprint $table) {
            $table->dropForeign('asignaciones_operador_id_foreign');
            $table->dropForeign('asignaciones_tracto_id_foreign');
            $table->dropForeign('asignaciones_equipo_id_foreign');
            $table->dropIndex(['operador_id', 'activa']);
            $table->dropIndex(['tracto_id', 'activa']);
        });

        Schema::table('asignaciones', function (Blueprint $table) {
            $table->string('estatus', 20)->default('Activa')->after('fecha_fin');
        });

        DB::statement("UPDATE `asignaciones` SET `estatus` = CASE WHEN `activa` = 1 THEN 'Activa' ELSE 'Inactiva' END");

        Schema::table('asignaciones', function (Blueprint $table) {
            $table->dropColumn(['activa', 'observaciones']);
            $table->foreign('operador_id')->references('id')->on('operadores')->cascadeOnDelete();
            $table->foreign('tracto_id')->references('id')->on('tractos')->cascadeOnDelete();
            $table->foreign('equipo_id')->references('id')->on('equipos')->cascadeOnDelete();
        });

        DB::statement('ALTER TABLE `asignaciones` CHANGE `fecha_inicio` `fecha_asignacion` DATE NOT NULL');
    }
};