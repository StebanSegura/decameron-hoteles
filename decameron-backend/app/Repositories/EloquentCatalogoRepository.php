<?php

namespace App\Repositories;

use App\Models\Acomodacion;
use App\Models\Ciudad;
use App\Models\TipoHabitacion;

class EloquentCatalogoRepository implements CatalogoRepositoryInterface
{
    public function ciudades()
    {
        return Ciudad::orderBy('nombre')->get();
    }

    public function tiposHabitacion()
    {
        return TipoHabitacion::orderBy('id')->get();
    }

    public function acomodaciones()
    {
        return Acomodacion::orderBy('id')->get();
    }

    public function acomodacionesPermitidas(int $tipoHabitacionId)
    {
        $tipo = TipoHabitacion::find($tipoHabitacionId);

        return $tipo ? $tipo->acomodacionesPermitidas()->orderBy('acomodaciones.id')->get() : collect();
    }

    public function existeTipoHabitacion(int $id): bool
    {
        return TipoHabitacion::whereKey($id)->exists();
    }

    public function existeAcomodacion(int $id): bool
    {
        return Acomodacion::whereKey($id)->exists();
    }

    public function nombreTipoHabitacion(int $id): ?string
    {
        return TipoHabitacion::find($id)?->nombre;
    }

    public function nombreAcomodacion(int $id): ?string
    {
        return Acomodacion::find($id)?->nombre;
    }

    public function combinacionValida(int $tipoHabitacionId, int $acomodacionId): bool
    {
        $tipo = TipoHabitacion::find($tipoHabitacionId);

        return $tipo ? $tipo->acomodacionesPermitidas()->where('acomodaciones.id', $acomodacionId)->exists() : false;
    }
}
