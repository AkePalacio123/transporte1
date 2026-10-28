<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('equipos', function (Blueprint $table) {

            $table->id();

            $table->string('numero_economico',20)->unique();

            $table->string('placa',20)->unique();

            $table->string('marca',50);

            $table->string('modelo',50);

            $table->year('anio');

            $table->string('tarjeta_circulacion')->nullable();

            $table->enum('estatus',[
                'Activo',
                'Mantenimiento',
                'Fuera de servicio'
            ])->default('Activo');

            $table->text('observaciones')->nullable();

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('equipos');
    }
};
