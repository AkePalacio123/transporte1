<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documentos_operador', function (Blueprint $table) {
            $table->id();

            // A qué operador pertenece. Si se borra el operador, se borran sus documentos.
            $table->foreignId('operador_id')
                  ->constrained('operadores')
                  ->cascadeOnDelete();

            $table->string('categoria', 50);       // Licencia federal, RFC, etc.
            $table->string('nombre_archivo');       // nombre original que subió el usuario
            $table->string('ruta');                 // dónde quedó guardado el archivo
            $table->date('fecha_vencimiento')->nullable();  // opcional
            $table->text('observaciones')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documentos_operador');
    }
};