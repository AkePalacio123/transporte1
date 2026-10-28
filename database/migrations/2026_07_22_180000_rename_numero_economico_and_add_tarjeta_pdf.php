<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    /**
     * Renombra numero_economico -> serie en tractos y equipos,
     * y agrega la columna tarjeta_pdf para el archivo escaneado.
     */
    public function up(): void
    {
        foreach (['tractos', 'equipos'] as $tabla) {

            // Renombrar la columna con SQL directo (evita problemas con
            // el indice unique y con doctrine/dbal sobre columnas year/date).
            DB::statement("ALTER TABLE `{$tabla}` CHANGE `numero_economico` `serie` VARCHAR(50) NOT NULL");

            // Agregar la columna del PDF de la tarjeta de circulacion.
            if (! Schema::hasColumn($tabla, 'tarjeta_pdf')) {
                Schema::table($tabla, function (Blueprint $table) {
                    $table->string('tarjeta_pdf', 255)
                        ->nullable()
                        ->after('tarjeta_circulacion');
                });
            }
        }
    }

    public function down(): void
    {
        foreach (['tractos', 'equipos'] as $tabla) {

            if (Schema::hasColumn($tabla, 'tarjeta_pdf')) {
                Schema::table($tabla, function (Blueprint $table) {
                    $table->dropColumn('tarjeta_pdf');
                });
            }

            DB::statement("ALTER TABLE `{$tabla}` CHANGE `serie` `numero_economico` VARCHAR(50) NOT NULL");
        }
    }
};