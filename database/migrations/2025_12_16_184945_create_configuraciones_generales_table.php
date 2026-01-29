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

            // Datos empresa
            $table->string('nombre_empresa');
            $table->string('logo')->nullable();

            $table->string('tax_id', 50)->nullable();
            
            $table->string('telefono')->nullable();
            $table->string('email')->nullable();
            $table->text('direccion')->nullable();
            $table->string('ciudad')->nullable();

            // Ubicación geográfica REAL
            $table->unsignedMediumInteger('country_id')->default(62);
            $table->unsignedMediumInteger('state_id')->nullable();

            // Moneda (editable)
            $table->string('currency', 10);
            $table->string('currency_name')->nullable();
            $table->string('currency_symbol')->nullable();

            // Zona horaria (NO editable)
            $table->string('timezone');

            $table->timestamps();

            // Relaciones
            $table->foreign('country_id')
                ->references('id')->on('countries')
                ->restrictOnDelete();

            $table->foreign('state_id')
                ->references('id')->on('states')
                ->nullOnDelete();

            $table->foreignId('tax_identifier_type_id')
                    ->nullable()
                    ->references('id')->on('tax_identifier_types')
                    ->nullOnDelete();;
            
            $table->foreignId('impuesto_id')
                ->nullable()
                ->constrained('impuestos')
                ->restrictOnDelete();

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
