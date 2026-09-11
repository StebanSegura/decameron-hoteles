<?php

namespace App\Repositories;

use App\Models\Hotel;

class EloquentHotelRepository implements HotelRepositoryInterface
{
    public function all()
    {
        return Hotel::with('ciudad')->orderBy('nombre')->get();
    }

    public function find(int $id): ?Hotel
    {
        return Hotel::with('ciudad')->find($id);
    }

    public function existsByNit(string $nit, ?int $excludeId = null): bool
    {
        return Hotel::where('nit', $nit)
            ->when($excludeId, fn ($q) => $q->whereKeyNot($excludeId))
            ->exists();
    }

    public function existsByNombreCiudad(string $nombre, int $ciudadId, ?int $excludeId = null): bool
    {
        return Hotel::whereRaw('LOWER(nombre) = ?', [strtolower($nombre)])
            ->where('ciudad_id', $ciudadId)
            ->when($excludeId, fn ($q) => $q->whereKeyNot($excludeId))
            ->exists();
    }

    public function create(array $data): Hotel
    {
        return Hotel::create($data)->load('ciudad');
    }

    public function update(Hotel $hotel, array $data): Hotel
    {
        $hotel->update($data);

        return $hotel->fresh('ciudad');
    }

    public function delete(Hotel $hotel): void
    {
        $hotel->delete();
    }
}
