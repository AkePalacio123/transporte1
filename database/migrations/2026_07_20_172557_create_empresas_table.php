<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('empresas', function (Blueprint $table) {

            $table->id();

            $table->string('nombre',100)->unique();

            $table->string('rfc',20)->nullable();

            $table->string('contacto',100)->nullable();

            $table->string('telefono',20)->nullable();

            $table->string('correo',100)->nullable();

            $table->enum('estatus',[
                'Activa',
                'Inactiva'
            ])->default('Activa');

            $table->text('observaciones')->nullable();

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('empresas');
    }
};