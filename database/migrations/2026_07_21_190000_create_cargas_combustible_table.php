<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cargas_combustible', function (Blueprint $table) {
            $table->id();

            // Un viaje puede tener varias cargas de combustible.
            $table->foreignId('viaje_id')
                ->constrained('viajes')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->string('estacion', 120);          // Gasolinera
            $table->date('fecha');
            $table->decimal('litros', 8, 2);
            $table->decimal('precio_litro', 8, 2)->default(0);
            $table->decimal('total', 10, 2);
            $table->unsignedInteger('kilometraje')->nullable();
            $table->string('folio_factura', 50)->nullable();
            $table->string('observaciones', 255)->nullable();

            $table->timestamps();

            $table->index(['viaje_id', 'fecha']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cargas_combustible');
    }
};