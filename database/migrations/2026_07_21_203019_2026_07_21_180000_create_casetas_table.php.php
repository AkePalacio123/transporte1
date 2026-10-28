<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('casetas', function (Blueprint $table) {
            $table->id();

            // Un viaje puede tener muchas casetas.
            // Si se elimina el viaje, sus casetas se van con el.
            $table->foreignId('viaje_id')
                ->constrained('viajes')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->string('nombre', 120);
            $table->decimal('costo', 10, 2)->default(0);
            $table->date('fecha')->nullable();
            $table->string('folio_ticket', 50)->nullable();
            $table->string('observaciones', 255)->nullable();

            $table->timestamps();

            $table->index(['viaje_id', 'fecha']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('casetas');
    }
};