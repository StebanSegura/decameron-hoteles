<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hoteles', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 150);
            $table->string('direccion', 200);
            $table->foreignId('ciudad_id')->constrained('ciudades')->restrictOnDelete();
            $table->string('nit', 30)->unique();
            $table->unsignedInteger('numero_habitaciones');
            $table->timestamps();

            // Regla: no deben existir hoteles repetidos (mismo nombre en la misma ciudad).
            $table->unique(['nombre', 'ciudad_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hoteles');
    }
};
