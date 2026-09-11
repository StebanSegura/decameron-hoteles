<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hotel_configuraciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')->constrained('hoteles')->cascadeOnDelete();
            $table->foreignId('tipo_habitacion_id')->constrained('tipos_habitacion')->restrictOnDelete();
            $table->foreignId('acomodacion_id')->constrained('acomodaciones')->restrictOnDelete();
            $table->unsignedInteger('cantidad');
            $table->timestamps();

            // Regla: no se repite tipo de habitación + acomodación para el mismo hotel.
            $table->unique(['hotel_id', 'tipo_habitacion_id', 'acomodacion_id'], 'ux_hotel_tipo_acomodacion');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hotel_configuraciones');
    }
};
