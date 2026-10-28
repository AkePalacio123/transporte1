<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('viajes', function (Blueprint $table) {

            $table->enum('estatus', [

                'Pendiente',

                'En ruta',

                'Finalizado',

                'Cancelado'

            ])->default('Pendiente')
              ->after('pago');

        });
    }

    public function down(): void
    {
        Schema::table('viajes', function (Blueprint $table) {

            $table->dropColumn('estatus');

        });
    }
};