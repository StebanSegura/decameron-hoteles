<?php

namespace App\Repositories;

use App\Models\HotelConfiguracion;

class EloquentConfiguracionRepository implements ConfiguracionRepositoryInterface
{
    public function byHotel(int $hotelId)
    {
        return HotelConfiguracion::with(['tipoHabitacion', 'acomodacion'])
            ->where('hotel_id', $hotelId)
            ->orderBy('tipo_habitacion_id')
            ->orderBy('acomodacion_id')
            ->get();
    }

    public function find(int $id): ?HotelConfiguracion
    {
        return HotelConfiguracion::with(['tipoHabitacion', 'acomodacion'])->find($id);
    }

    public function existsCombinacion(int $hotelId, int $tipoHabitacionId, int $acomodacionId, ?int $excludeId = null): bool
    {
        return HotelConfiguracion::where('hotel_id', $hotelId)
            ->where('tipo_habitacion_id', $tipoHabitacionId)
            ->where('acomodacion_id', $acomodacionId)
            ->when($excludeId, fn ($q) => $q->whereKeyNot($excludeId))
            ->exists();
    }

    public function totalConfigurado(int $hotelId, ?int $excludeId = null): int
    {
        return (int) HotelConfiguracion::where('hotel_id', $hotelId)
            ->when($excludeId, fn ($q) => $q->whereKeyNot($excludeId))
            ->sum('cantidad');
    }

    public function create(array $data): HotelConfiguracion
    {
        return HotelConfiguracion::create($data)->load(['tipoHabitacion', 'acomodacion']);
    }

    public function update(HotelConfiguracion $config, array $data): HotelConfiguracion
    {
        $config->update($data);

        return $config->fresh(['tipoHabitacion', 'acomodacion']);
    }

    public function delete(HotelConfiguracion $config): void
    {
        $config->delete();
    }
}
