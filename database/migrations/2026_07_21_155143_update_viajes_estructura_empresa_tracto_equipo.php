<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. El folio ahora puede ir vacío (EMMSA no lo usa),
        //    pero si existe, sigue siendo único.
        Schema::table('viajes', function (Blueprint $table) {
            $table->string('folio', 30)->nullable()->change();
        });

        // 2. Eliminamos la columna ambigua unidad_id.
        //    La tabla está vacía, así que no perdemos nada.
        Schema::table('viajes', function (Blueprint $table) {
            $table->dropConstrainedForeignId('unidad_id');
        });

        // 3. Agregamos las relaciones reales del negocio.
        Schema::table('viajes', function (Blueprint $table) {
            $table->foreignId('empresa_id')
                ->after('cataporte')
                ->constrained('empresas')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('tracto_id')
                ->after('empresa_id')
                ->constrained('tractos')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('equipo_id')
                ->nullable()
                ->after('tracto_id')
                ->constrained('equipos')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('viajes', function (Blueprint $table) {
            $table->dropConstrainedForeignId('equipo_id');
            $table->dropConstrainedForeignId('tracto_id');
            $table->dropConstrainedForeignId('empresa_id');

            $table->foreignId('unidad_id')->nullable()->constrained('tractos');
            $table->string('folio', 30)->nullable(false)->change();
        });
    }
};