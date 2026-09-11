<?php

namespace Database\Factories;

use App\Models\Ciudad;
use Illuminate\Database\Eloquent\Factories\Factory;

class CiudadFactory extends Factory
{
    protected $model = Ciudad::class;

    public function definition(): array
    {
        return [
            'nombre' => strtoupper($this->faker->unique()->city()),
        ];
    }
}
