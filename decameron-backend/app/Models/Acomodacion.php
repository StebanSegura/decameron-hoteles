<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Acomodacion extends Model
{
    protected $table = 'acomodaciones';
    public $timestamps = false;

    protected $fillable = ['nombre'];

    public function tiposHabitacion()
    {
        return $this->belongsToMany(
            TipoHabitacion::class,
            'tipo_habitacion_acomodacion',
            'acomodacion_id',
            'tipo_habitacion_id'
        );
    }
}
