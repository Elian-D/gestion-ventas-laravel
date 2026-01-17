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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();

            // Tipo de cliente
            $table->string('type', 20); // individual / company

            // Estado del cliente
            $table->foreignId('estado_cliente_id')
                ->constrained('estados_clientes')
                ->restrictOnDelete();

            // Datos legales / contacto
            $table->string('name'); // Nombre del cliente o razón social
            $table->string('commercial_name')->nullable(); // Nombre comercial si aplica
            $table->string('email')->nullable();
            $table->string('phone')->nullable();

            // Ubicación del cliente (geográfica, país heredado)
            $table->unsignedMediumInteger('state_id');
            $table->string('city', 100);

            // Identificación fiscal
            $table->foreignId('tax_identifier_type_id')
                ->nullable()
                ->constrained('tax_identifier_types')
                ->nullOnDelete();
            $table->string('tax_id', 50)
                ->nullable()
                ->unique();

            // Control operativo
            $table->boolean('active')->default(true);

            $table->timestamps();
            $table->softDeletes();

            // Relaciones
            $table->foreign('state_id')
                ->references('id')->on('states')
                ->restrictOnDelete();
            
            // Índices
            $table->index('tax_id');
        });
        ;
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
