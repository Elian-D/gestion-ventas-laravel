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
        Schema::create('pos_terminals', function (Blueprint $table) {
            $table->id();
            $table->string('name'); 
            
            // Relación con Almacenes (De dónde descuenta esta terminal)
            $table->foreignId('warehouse_id')
                ->constrained('warehouses')
                ->onDelete('restrict');

            // Relación con el Catálogo de Cuentas (Cuenta de Activo/Caja específica)
            $table->foreignId('cash_account_id')
                ->constrained('accounting_accounts')
                ->onDelete('restrict');

            // Configuración de Facturación por defecto
            $table->foreignId('default_ncf_type_id')
                ->nullable()
                ->constrained('ncf_types')
                ->onDelete('set null');

            $table->foreignId('default_client_id')
                ->nullable()
                ->constrained('clients')
                ->onDelete('set null');

            // Atributos de Interfaz y Hardware
            $table->boolean('is_mobile')->default(false); // Para lógica Sunmi/Móvil
            $table->string('printer_format')->default('80mm'); // '80mm' o '58mm'
            
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pos_terminals');
    }
};
