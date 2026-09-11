<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tipo_habitacion_acomodacion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tipo_habitacion_id')->constrained('tipos_habitacion')->cascadeOnDelete();
            $table->foreignId('acomodacion_id')->constrained('acomodaciones')->cascadeOnDelete();
            $table->unique(['tipo_habitacion_id', 'acomodacion_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tipo_habitacion_acomodacion');
    }
};
