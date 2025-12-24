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
        Schema::create('estados_clientes', function (Blueprint $table) {
            $table->id();

            $table->string('nombre')->unique();

            // Control del catálogo
            $table->boolean('activo')->default(true);

            // Comportamiento de negocio
            $table->boolean('permite_operar')->default(true);
            $table->boolean('permite_facturar')->default(true);

            $table->string('clase_fondo', 100)->nullable();
            $table->string('clase_texto', 100)->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estados_clientes');
    }
};
