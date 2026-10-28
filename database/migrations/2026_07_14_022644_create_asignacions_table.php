<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asignaciones', function (Blueprint $table) {

            $table->id();

            $table->foreignId('operador_id')
                    ->constrained('operadores')
                    ->cascadeOnDelete();

            $table->foreignId('tracto_id')
                    ->constrained('tractos')
                    ->cascadeOnDelete();

            $table->foreignId('equipo_id')
                    ->constrained('equipos')
                    ->cascadeOnDelete();

            $table->date('fecha_asignacion');

            $table->date('fecha_fin')->nullable();

            $table->enum('estatus',[
                'Activa',
                'Finalizada'
            ])->default('Activa');

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asignaciones');
    }
};