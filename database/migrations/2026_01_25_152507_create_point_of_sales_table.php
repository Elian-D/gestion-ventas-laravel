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
        Schema::create('point_of_sales', function (Blueprint $table) {
            $table->id();

            // Relación con cliente
            $table->foreignId('client_id')
                ->constrained('clients')
                ->cascadeOnDelete();

            // Tipo de negocio
            $table->foreignId('business_type_id')
                ->constrained('business_types')
                ->restrictOnDelete();

            // Identificación del PDV
            $table->string('name', 150); // Nombre comercial del PDV
            $table->string('code', 50)->nullable()->unique(); // Código interno opcional

            // Ubicación
            $table->unsignedMediumInteger('state_id');
            $table->string('city', 100);
            $table->string('address', 255)->nullable();

            // Coordenadas (rutas / mapas)
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            // Contacto operativo
            $table->string('contact_name')->nullable();
            $table->string('contact_phone')->nullable();

            // Observaciones
            $table->text('notes')->nullable();

            // Control operativo
            $table->boolean('active')->default(true);

            $table->timestamps();
            $table->softDeletes();

            // Relaciones
            $table->foreign('state_id')
                ->references('id')->on('states')
                ->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('point_of_sales');
    }
};
