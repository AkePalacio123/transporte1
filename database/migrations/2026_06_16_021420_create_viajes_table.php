<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('viajes', function (Blueprint $table) {

            $table->id();

            // Folio de la bitácora
            $table->string('folio', 30)->unique();

            // Tipo de viaje
            $table->enum('tipo_viaje', [
                'Normal',
                'Retorno'
            ])->default('Normal');

            // Fechas
            $table->date('fecha_viaje');
            $table->date('fecha_captura');
            $table->date('fecha_pago')->nullable();

            // Información del viaje
            $table->string('origen', 150);
            $table->string('destino', 150);

            // Pago del viaje
            $table->decimal('pago', 10, 2)->default(0);

            // Estado del pago
            $table->enum('estatus_pago', [
                'Pendiente',
                'Pagado'
            ])->default('Pendiente');

            // Estado de la bitácora
            $table->enum('estatus_bitacora', [
                'No entregada',
                'Entregada'
            ])->default('No entregada');

            // Persona que recibió la bitácora
            $table->string('entregado_a', 100)->nullable();

            // Fecha en que se entregó la bitácora
            $table->date('fecha_entrega_bitacora')->nullable();

            // Observaciones
            $table->text('observaciones')->nullable();

            // Archivo PDF de la bitácora
            $table->string('archivo_bitacora')->nullable();

            /*
            |--------------------------------------------------------------------------
            | Relaciones
            |--------------------------------------------------------------------------
            */

            // Operador que realizó el viaje
            $table->foreignId('operador_id')
                ->constrained('operadores')
                ->cascadeOnDelete();

            // Tracto utilizado en el viaje
            $table->foreignId('tracto_id')
                ->constrained('tractos')
                ->cascadeOnDelete();

            // Equipo (caja) utilizado en el viaje
            $table->foreignId('equipo_id')
                ->constrained('equipos')
                ->cascadeOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('viajes');
    }
};