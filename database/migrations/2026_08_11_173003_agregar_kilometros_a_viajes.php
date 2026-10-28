<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('viajes', function (Blueprint $table) {
            // Kilómetros recorridos en el viaje (ida y vuelta), captura manual.
            $table->unsignedInteger('kilometros')->nullable()->after('destino');
        });
    }

    public function down(): void
    {
        Schema::table('viajes', function (Blueprint $table) {
            $table->dropColumn('kilometros');
        });
    }
};