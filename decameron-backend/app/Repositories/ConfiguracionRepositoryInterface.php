<?php

namespace App\Repositories;

use App\Models\HotelConfiguracion;

interface ConfiguracionRepositoryInterface
{
    public function byHotel(int $hotelId);

    public function find(int $id): ?HotelConfiguracion;

    public function existsCombinacion(int $hotelId, int $tipoHabitacionId, int $acomodacionId, ?int $excludeId = null): bool;

    public function totalConfigurado(int $hotelId, ?int $excludeId = null): int;

    public function create(array $data): HotelConfiguracion;

    public function update(HotelConfiguracion $config, array $data): HotelConfiguracion;

    public function delete(HotelConfiguracion $config): void;
}
