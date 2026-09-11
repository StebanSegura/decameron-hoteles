<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreConfiguracionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tipo_habitacion_id' => ['required', 'integer', 'exists:tipos_habitacion,id'],
            'acomodacion_id' => ['required', 'integer', 'exists:acomodaciones,id'],
            'cantidad' => ['required', 'integer', 'min:1'],
        ];
    }
}
