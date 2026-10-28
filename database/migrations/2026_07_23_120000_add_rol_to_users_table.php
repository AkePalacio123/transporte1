<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('rol', ['Administrador', 'Capturista', 'Consulta'])
                ->default('Capturista')
                ->after('email');

            $table->boolean('activo')->default(true)->after('rol');
        });

        // El primer usuario registrado queda como Administrador,
        // para que siempre exista al menos un admin en el sistema.
        DB::table('users')->orderBy('id')->limit(1)->update(['rol' => 'Administrador']);
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['rol', 'activo']);
        });
    }
};