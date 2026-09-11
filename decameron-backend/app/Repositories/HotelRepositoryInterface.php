<?php

namespace App\Repositories;

use App\Models\Hotel;

interface HotelRepositoryInterface
{
    public function all();

    public function find(int $id): ?Hotel;

    public function existsByNit(string $nit, ?int $excludeId = null): bool;

    public function existsByNombreCiudad(string $nombre, int $ciudadId, ?int $excludeId = null): bool;

    public function create(array $data): Hotel;

    public function update(Hotel $hotel, array $data): Hotel;

    public function delete(Hotel $hotel): void;
}
