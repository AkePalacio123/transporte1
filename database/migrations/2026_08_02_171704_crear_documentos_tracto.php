<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documentos_tracto', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tracto_id')
                  ->constrained('tractos')
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
        Schema::dropIfExists('documentos_tracto');
    }
};