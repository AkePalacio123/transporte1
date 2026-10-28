<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('operadores', function (Blueprint $table) {
            $table->id();

            $table->string('nombre', 100);
            $table->string('telefono', 20);
            $table->string('licencia', 50)->unique();

            $table->date('vencimiento_licencia');

            $table->enum('estatus', [
                'Activo',
                'Inactivo',
                'Suspendido'
            ])->default('Activo');

            $table->foreignId('tracto_id')
            ->nullable()
            ->constrained('tractos')
            ->nullOnDelete();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('operadores');
    }
};