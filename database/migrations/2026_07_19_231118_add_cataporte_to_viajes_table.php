<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('viajes', function (Blueprint $table) {

            $table->string('cataporte', 30)
                  ->nullable()
                  ->after('folio');

        });
    }

    public function down(): void
    {
        Schema::table('viajes', function (Blueprint $table) {

            $table->dropColumn('cataporte');

        });
    }
};