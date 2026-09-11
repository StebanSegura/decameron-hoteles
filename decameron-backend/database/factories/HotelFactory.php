<?php

namespace Database\Factories;

use App\Models\Ciudad;
use App\Models\Hotel;
use Illuminate\Database\Eloquent\Factories\Factory;

class HotelFactory extends Factory
{
    protected $model = Hotel::class;

    public function definition(): array
    {
        return [
            'nombre' => 'HOTEL '.strtoupper($this->faker->unique()->city()),
            'direccion' => $this->faker->streetAddress(),
            'ciudad_id' => Ciudad::factory(),
            'nit' => $this->faker->unique()->numerify('########-#'),
            'numero_habitaciones' => $this->faker->numberBetween(10, 100),
        ];
    }
}
