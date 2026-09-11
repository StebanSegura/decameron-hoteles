<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HotelConfiguracionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'hotel_id' => $this->hotel_id,
            'tipo_habitacion_id' => $this->tipo_habitacion_id,
            'tipo_habitacion_nombre' => $this->whenLoaded('tipoHabitacion', fn () => $this->tipoHabitacion->nombre),
            'acomodacion_id' => $this->acomodacion_id,
            'acomodacion_nombre' => $this->whenLoaded('acomodacion', fn () => $this->acomodacion->nombre),
            'cantidad' => $this->cantidad,
        ];
    }
}
