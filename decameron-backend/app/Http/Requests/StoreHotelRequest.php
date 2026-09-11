<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreHotelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:150'],
            'direccion' => ['required', 'string', 'max:200'],
            'ciudad_id' => ['required', 'integer', 'exists:ciudades,id'],
            'nit' => ['required', 'string', 'max:30'],
            'numero_habitaciones' => ['required', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'ciudad_id.exists' => 'Debe seleccionar una ciudad válida.',
            'numero_habitaciones.min' => 'El número de habitaciones debe ser mayor a cero.',
        ];
    }
}
