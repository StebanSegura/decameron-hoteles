<?php

namespace App\Repositories;

interface CatalogoRepositoryInterface
{
    public function ciudades();

    public function tiposHabitacion();

    public function acomodaciones();

    public function acomodacionesPermitidas(int $tipoHabitacionId);

    public function existeTipoHabitacion(int $id): bool;

    public function existeAcomodacion(int $id): bool;

    public function nombreTipoHabitacion(int $id): ?string;

    public function nombreAcomodacion(int $id): ?string;

    public function combinacionValida(int $tipoHabitacionId, int $acomodacionId): bool;
}
