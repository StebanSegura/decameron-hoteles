<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class TipoHabitacion extends Model
{
    protected $table = 'tipos_habitacion';
    public $timestamps = false;

    protected $fillable = ['nombre'];

    /**
     * Acomodaciones permitidas para este tipo de habitación
     * (catálogo tipo_habitacion_acomodacion).
     */
    public function acomodacionesPermitidas(): BelongsToMany
    {
        return $this->belongsToMany(
            Acomodacion::class,
            'tipo_habitacion_acomodacion',
            'tipo_habitacion_id',
            'acomodacion_id'
        );
    }
}
