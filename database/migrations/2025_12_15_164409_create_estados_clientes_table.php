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
            $table->string('nombre');
            $table->boolean('estado')->default(true);
            $table->timestamps();
            $table->string('clase_fondo', 100)->default('bg-gray-200'); // Ejemplo de clase por defecto
            $table->string('clase_texto', 100)->default('text-gray-800'); // Ejemplo de clase por defecto
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
