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
        Schema::create('configuraciones_generales', function (Blueprint $table) {
            $table->id();

            // Datos de empresa
            $table->string('nombre_empresa');
            $table->string('logo')->nullable();
            $table->string('telefono')->nullable();
            $table->string('email')->nullable();
            $table->text('direccion')->nullable();
            $table->string('ciudad')->nullable();
            $table->string('pais')->nullable();

            // Relaciones clave
            $table->foreignId('moneda_id')
                ->constrained('monedas')
                ->restrictOnDelete();

            $table->foreignId('impuesto_id')
                ->constrained('impuestos')
                ->restrictOnDelete();

            $table->string('timezone')->default('America/Santo_Domingo');

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('configuraciones_generales');
    }
};
