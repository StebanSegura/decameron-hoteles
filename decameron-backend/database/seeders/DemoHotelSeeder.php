<?php

namespace Database\Seeders;

use App\Models\Acomodacion;
use App\Models\Ciudad;
use App\Models\Hotel;
use App\Models\TipoHabitacion;
use Illuminate\Database\Seeder;

class DemoHotelSeeder extends Seeder
{
    public function run(): void
    {
        $cartagena = Ciudad::where('nombre', 'CARTAGENA')->firstOrFail();

        $hotel = Hotel::firstOrCreate(
            ['nit' => '12345678-9'],
            [
                'nombre' => 'DECAMERON CARTAGENA',
                'direccion' => 'CALLE 23 58-25',
                'ciudad_id' => $cartagena->id,
                'numero_habitaciones' => 42,
            ]
        );

        $estandar = TipoHabitacion::where('nombre', 'ESTANDAR')->firstOrFail();
        $junior = TipoHabitacion::where('nombre', 'JUNIOR')->firstOrFail();
        $sencilla = Acomodacion::where('nombre', 'SENCILLA')->firstOrFail();
        $doble = Acomodacion::where('nombre', 'DOBLE')->firstOrFail();
        $triple = Acomodacion::where('nombre', 'TRIPLE')->firstOrFail();

        $hotel->configuraciones()->firstOrCreate(
            ['tipo_habitacion_id' => $estandar->id, 'acomodacion_id' => $sencilla->id],
            ['cantidad' => 25]
        );
        $hotel->configuraciones()->firstOrCreate(
            ['tipo_habitacion_id' => $junior->id, 'acomodacion_id' => $triple->id],
            ['cantidad' => 12]
        );
        $hotel->configuraciones()->firstOrCreate(
            ['tipo_habitacion_id' => $estandar->id, 'acomodacion_id' => $doble->id],
            ['cantidad' => 5]
        );
    }
}
