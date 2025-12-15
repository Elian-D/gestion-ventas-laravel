<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('municipios', function (Blueprint $table) {
            $table->id();

            $table->foreignId('provincia_id')
                  ->constrained('provincias')
                  ->cascadeOnDelete();

            $table->string('nombre');
            $table->boolean('estado')->default(true);
            $table->timestamps();
            $table->softDeletes();


            $table->unique(['provincia_id', 'nombre']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('municipios');
    }
};
