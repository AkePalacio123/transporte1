<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documentos_equipo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipo_id')
                  ->constrained('equipos')
                  ->cascadeOnDelete();
            $table->string('categoria', 50);
            $table->string('nombre_archivo');
            $table->string('ruta');
            $table->date('fecha_vencimiento')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documentos_equipo');
    }
};