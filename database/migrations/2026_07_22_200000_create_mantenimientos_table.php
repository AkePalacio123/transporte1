<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mantenimientos', function (Blueprint $table) {
            $table->id();

            // Un mantenimiento pertenece a un tracto O a un equipo.
            // Solo una de las dos columnas se llena; la validacion lo garantiza.
            $table->foreignId('tracto_id')
                ->nullable()
                ->constrained('tractos')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('equipo_id')
                ->nullable()
                ->constrained('equipos')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->enum('tipo', ['Preventivo', 'Correctivo'])->default('Preventivo');
            $table->enum('estatus', ['En taller', 'Finalizado', 'Cancelado'])->default('En taller');

            $table->date('fecha_entrada');
            $table->date('fecha_salida')->nullable();

            $table->unsignedInteger('kilometraje')->nullable();
            $table->string('taller', 120)->nullable();

            $table->text('descripcion');
            $table->decimal('costo_mano_obra', 10, 2)->default(0);

            $table->string('folio_factura', 50)->nullable();
            $table->string('factura_pdf', 255)->nullable();
            $table->text('observaciones')->nullable();

            $table->timestamps();

            $table->index(['tracto_id', 'fecha_entrada']);
            $table->index(['equipo_id', 'fecha_entrada']);
            $table->index('estatus');
        });

        Schema::create('piezas_mantenimiento', function (Blueprint $table) {
            $table->id();

            $table->foreignId('mantenimiento_id')
                ->constrained('mantenimientos')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->string('nombre', 150);
            $table->string('numero_parte', 60)->nullable();
            $table->string('proveedor', 120)->nullable();
            $table->decimal('cantidad', 8, 2)->default(1);
            $table->decimal('precio_unitario', 10, 2)->default(0);
            $table->decimal('subtotal', 10, 2)->default(0);

            $table->timestamps();

            $table->index('mantenimiento_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('piezas_mantenimiento');
        Schema::dropIfExists('mantenimientos');
    }
};