<?php

namespace Tests\Feature;

use App\Models\Acomodacion;
use App\Models\Hotel;
use App\Models\TipoHabitacion;
use Database\Seeders\CatalogoSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ConfiguracionApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(CatalogoSeeder::class);
    }

    public function test_puede_asignar_una_configuracion_valida(): void
    {
        $hotel = Hotel::factory()->create(['numero_habitaciones' => 42]);
        $estandar = TipoHabitacion::where('nombre', 'ESTANDAR')->first();
        $sencilla = Acomodacion::where('nombre', 'SENCILLA')->first();

        $response = $this->postJson("/api/hoteles/{$hotel->id}/configuraciones", [
            'tipo_habitacion_id' => $estandar->id,
            'acomodacion_id' => $sencilla->id,
            'cantidad' => 25,
        ]);

        $response->assertStatus(201)->assertJsonPath('data.cantidad', 25);
    }

    public function test_rechaza_acomodacion_no_valida_para_el_tipo(): void
    {
        // ESTANDAR no admite TRIPLE (solo Sencilla/Doble).
        $hotel = Hotel::factory()->create(['numero_habitaciones' => 42]);
        $estandar = TipoHabitacion::where('nombre', 'ESTANDAR')->first();
        $triple = Acomodacion::where('nombre', 'TRIPLE')->first();

        $response = $this->postJson("/api/hoteles/{$hotel->id}/configuraciones", [
            'tipo_habitacion_id' => $estandar->id,
            'acomodacion_id' => $triple->id,
            'cantidad' => 5,
        ]);

        $response->assertStatus(422);
    }

    public function test_rechaza_cantidad_que_supera_el_maximo_del_hotel(): void
    {
        $hotel = Hotel::factory()->create(['numero_habitaciones' => 10]);
        $estandar = TipoHabitacion::where('nombre', 'ESTANDAR')->first();
        $sencilla = Acomodacion::where('nombre', 'SENCILLA')->first();

        $response = $this->postJson("/api/hoteles/{$hotel->id}/configuraciones", [
            'tipo_habitacion_id' => $estandar->id,
            'acomodacion_id' => $sencilla->id,
            'cantidad' => 11,
        ]);

        $response->assertStatus(422);
    }

    public function test_rechaza_combinacion_duplicada_para_el_mismo_hotel(): void
    {
        $hotel = Hotel::factory()->create(['numero_habitaciones' => 42]);
        $estandar = TipoHabitacion::where('nombre', 'ESTANDAR')->first();
        $sencilla = Acomodacion::where('nombre', 'SENCILLA')->first();

        $hotel->configuraciones()->create([
            'tipo_habitacion_id' => $estandar->id,
            'acomodacion_id' => $sencilla->id,
            'cantidad' => 10,
        ]);

        $response = $this->postJson("/api/hoteles/{$hotel->id}/configuraciones", [
            'tipo_habitacion_id' => $estandar->id,
            'acomodacion_id' => $sencilla->id,
            'cantidad' => 5,
        ]);

        $response->assertStatus(409);
    }

    public function test_permite_sumar_varias_configuraciones_hasta_el_maximo(): void
    {
        $hotel = Hotel::factory()->create(['numero_habitaciones' => 42]);
        $estandar = TipoHabitacion::where('nombre', 'ESTANDAR')->first();
        $junior = TipoHabitacion::where('nombre', 'JUNIOR')->first();
        $sencilla = Acomodacion::where('nombre', 'SENCILLA')->first();
        $doble = Acomodacion::where('nombre', 'DOBLE')->first();
        $triple = Acomodacion::where('nombre', 'TRIPLE')->first();

        $this->postJson("/api/hoteles/{$hotel->id}/configuraciones", [
            'tipo_habitacion_id' => $estandar->id, 'acomodacion_id' => $sencilla->id, 'cantidad' => 25,
        ])->assertStatus(201);

        $this->postJson("/api/hoteles/{$hotel->id}/configuraciones", [
            'tipo_habitacion_id' => $junior->id, 'acomodacion_id' => $triple->id, 'cantidad' => 12,
        ])->assertStatus(201);

        $this->postJson("/api/hoteles/{$hotel->id}/configuraciones", [
            'tipo_habitacion_id' => $estandar->id, 'acomodacion_id' => $doble->id, 'cantidad' => 5,
        ])->assertStatus(201);

        // 25 + 12 + 5 = 42, exactamente el máximo del hotel.
        $this->getJson("/api/hoteles/{$hotel->id}/configuraciones")
            ->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }
}
