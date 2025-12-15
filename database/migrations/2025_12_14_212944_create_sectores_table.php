<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sectores', function (Blueprint $table) {
            $table->id();

            $table->foreignId('municipio_id')
                  ->constrained('municipios')
                  ->cascadeOnDelete();

            $table->string('nombre');
            $table->boolean('estado')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['municipio_id', 'nombre']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sectores');
    }
};
