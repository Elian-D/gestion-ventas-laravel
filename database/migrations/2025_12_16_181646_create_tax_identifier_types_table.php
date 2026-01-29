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
        Schema::create('tax_identifier_types', function (Blueprint $table) {
            $table->id();

            // Relación con countries (mediumint unsigned)
            $table->mediumInteger('country_id')->unsigned();

            // Tipo de entidad
            $table->enum('entity_type', ['person', 'company', 'both']);

            // Código corto: RNC, CPF, RUT, SIN, etc.
            $table->string('code', 20);

            // Nombre oficial largo
            $table->string('name');

            // VAT si aplica (opcional)
            $table->string('vat_name')->nullable();

            // Validación futura
            $table->string('regex')->nullable();
            $table->string('example')->nullable();

            // Flags útiles
            $table->boolean('requires_postal_code')->default(false);

            $table->timestamps();

            $table->foreign('country_id')
                ->references('id')
                ->on('countries')
                ->cascadeOnDelete();

            // Un país no debe repetir el mismo código
            $table->unique(['country_id', 'code']);
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tax_identifier_types');
    }
};
