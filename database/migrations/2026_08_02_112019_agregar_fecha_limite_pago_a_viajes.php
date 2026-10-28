<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Agrega la columna fecha_limite_pago a la tabla viajes
     * y rellena los viajes existentes con (fecha_viaje + 1 mes).
     */
    public function up(): void
    {
        Schema::table('viajes', function (Blueprint $table) {
            // Se coloca después de fecha_pago para que quede junto a lo de pagos.
            $table->date('fecha_limite_pago')->nullable()->after('fecha_pago');
        });

        // Rellenar los viajes que ya existían: límite = fecha_viaje + 1 mes.
        // Se hace con SQL directo para no depender de los modelos.
        DB::statement("
            UPDATE viajes
            SET fecha_limite_pago = DATE_ADD(fecha_viaje, INTERVAL 1 MONTH)
            WHERE fecha_limite_pago IS NULL
              AND fecha_viaje IS NOT NULL
        ");
    }

    public function down(): void
    {
        Schema::table('viajes', function (Blueprint $table) {
            $table->dropColumn('fecha_limite_pago');
        });
    }
};