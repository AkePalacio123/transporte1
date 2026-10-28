<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ajustes finales de la tabla viajes:
     *  - cataporte pasa a ser obligatorio (contabilidad lo requiere siempre).
     *  - operador_id deja de borrar viajes en cascada, para proteger
     *    el historial de pagos si se elimina un operador.
     */
    public function up(): void
    {
        // 1. cataporte obligatorio.
        //    Se usa SQL directo para evitar problemas de doctrine/dbal
        //    con las columnas ENUM de esta tabla.
        DB::statement('ALTER TABLE `viajes` MODIFY `cataporte` VARCHAR(30) NOT NULL');

        // 2. operador_id: de CASCADE a RESTRICT.
        Schema::table('viajes', function (Blueprint $table) {
            $table->dropForeign('viajes_operador_id_foreign');
        });

        Schema::table('viajes', function (Blueprint $table) {
            $table->foreign('operador_id')
                ->references('id')->on('operadores')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
        });
    }

    /**
     * Revierte los cambios.
     */
    public function down(): void
    {
        Schema::table('viajes', function (Blueprint $table) {
            $table->dropForeign(['operador_id']);
        });

        Schema::table('viajes', function (Blueprint $table) {
            $table->foreign('operador_id')
                ->references('id')->on('operadores')
                ->cascadeOnDelete();
        });

        DB::statement('ALTER TABLE `viajes` MODIFY `cataporte` VARCHAR(30) NULL');
    }
};