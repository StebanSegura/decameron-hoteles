<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HotelResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'direccion' => $this->direccion,
            'ciudad_id' => $this->ciudad_id,
            'ciudad_nombre' => $this->whenLoaded('ciudad', fn () => $this->ciudad->nombre),
            'nit' => $this->nit,
            'numero_habitaciones' => $this->numero_habitaciones,
        ];
    }
}
