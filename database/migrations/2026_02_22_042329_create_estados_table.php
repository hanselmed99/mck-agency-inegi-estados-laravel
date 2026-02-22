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
        Schema::create('estados', function (Blueprint $table) {
            $table->id();
            $table->string('cvegeo', 2)->unique();       // Clave geoestadística
            $table->string('cve_ent', 2)->nullable();                // Clave del AGEE
            $table->string('nomgeo', 50);                // Nombre del estado
            $table->string('nom_abrev', 10)->nullable(); // Nombre abreviado
            $table->string('pob_total', 10)->nullable();
            $table->string('pob_femenina', 10)->nullable();
            $table->string('pob_masculina', 10)->nullable();
            $table->string('total_viviendas_habitadas', 10)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estados');
    }
};
