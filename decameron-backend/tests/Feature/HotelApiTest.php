<?php

namespace Tests\Feature;

use App\Models\Ciudad;
use App\Models\Hotel;
use Database\Seeders\CatalogoSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HotelApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(CatalogoSeeder::class);
    }

    public function test_puede_crear_un_hotel(): void
    {
        $ciudad = Ciudad::where('nombre', 'CARTAGENA')->first();

        $response = $this->postJson('/api/hoteles', [
            'nombre' => 'DECAMERON CARTAGENA',
            'direccion' => 'CALLE 23 58-25',
            'ciudad_id' => $ciudad->id,
            'nit' => '12345678-9',
            'numero_habitaciones' => 42,
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.nombre', 'DECAMERON CARTAGENA')
            ->assertJsonPath('data.nit', '12345678-9');

        $this->assertDatabaseHas('hoteles', ['nit' => '12345678-9']);
    }

    public function test_no_permite_hoteles_con_nit_repetido(): void
    {
        $ciudad = Ciudad::where('nombre', 'CARTAGENA')->first();
        Hotel::factory()->create(['nit' => '12345678-9', 'ciudad_id' => $ciudad->id]);

        $response = $this->postJson('/api/hoteles', [
            'nombre' => 'OTRO HOTEL',
            'direccion' => 'OTRA DIRECCIÓN',
            'ciudad_id' => $ciudad->id,
            'nit' => '12345678-9',
            'numero_habitaciones' => 10,
        ]);

        $response->assertStatus(409);
    }

    public function test_no_permite_hoteles_con_mismo_nombre_en_la_misma_ciudad(): void
    {
        $ciudad = Ciudad::where('nombre', 'CARTAGENA')->first();
        Hotel::factory()->create(['nombre' => 'DECAMERON CARTAGENA', 'ciudad_id' => $ciudad->id]);

        $response = $this->postJson('/api/hoteles', [
            'nombre' => 'decameron cartagena',
            'direccion' => 'OTRA DIRECCIÓN',
            'ciudad_id' => $ciudad->id,
            'nit' => '99999999-9',
            'numero_habitaciones' => 10,
        ]);

        $response->assertStatus(409);
    }

    public function test_valida_campos_obligatorios(): void
    {
        $response = $this->postJson('/api/hoteles', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['nombre', 'direccion', 'ciudad_id', 'nit', 'numero_habitaciones']);
    }

    public function test_puede_listar_y_eliminar_un_hotel(): void
    {
        $hotel = Hotel::factory()->create();

        $this->getJson('/api/hoteles')->assertStatus(200)->assertJsonCount(1, 'data');

        $this->deleteJson("/api/hoteles/{$hotel->id}")->assertStatus(200);

        $this->assertDatabaseMissing('hoteles', ['id' => $hotel->id]);
    }
}
