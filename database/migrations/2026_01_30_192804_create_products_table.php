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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')
                    ->constrained('categories')
                    ->cascadeOnDelete();

            $table->foreignId('unit_id')
                    ->constrained('units')
                    ->cascadeOnDelete();

            $table->string('name');
            $table->string('slug')->unique();
            $table->string('sku')->unique()->nullable();
            $table->text('description')->nullable();
            $table->string('image_path')->nullable();

            // Contabilidad y Precios
            $table->decimal('price', 12, 2)->default(0);
            $table->decimal('cost', 12, 2)->default(0);

            // Inventario Operativo
            $table->integer('stock')->default(0);
            $table->integer('min_stock')->default(5);

            // Flags
            $table->boolean('is_active')->default(true);
            $table->boolean('is_stockable')->default(true);

            $table->timestamps();
            $table->softDeletes(); // Requerido por tu checklist
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
