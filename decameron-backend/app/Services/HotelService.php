<?php

namespace App\Services;

use App\Exceptions\BusinessRuleException;
use App\Models\Hotel;
use App\Repositories\CatalogoRepositoryInterface;
use App\Repositories\HotelRepositoryInterface;

class HotelService
{
    public function __construct(
        private HotelRepositoryInterface $hoteles,
        private CatalogoRepositoryInterface $catalogo,
    ) {
    }

    public function listar()
    {
        return $this->hoteles->all();
    }

    public function obtener(int $id): Hotel
    {
        $hotel = $this->hoteles->find($id);

        if (! $hotel) {
            throw new BusinessRuleException("El hotel con id {$id} no existe.", 404);
        }

        return $hotel;
    }

    public function crear(array $data): Hotel
    {
        $this->validarDuplicados($data['nit'], $data['nombre'], $data['ciudad_id']);

        return $this->hoteles->create($data);
    }

    public function actualizar(int $id, array $data): Hotel
    {
        $hotel = $this->obtener($id);
        $this->validarDuplicados($data['nit'], $data['nombre'], $data['ciudad_id'], $id);

        return $this->hoteles->update($hotel, $data);
    }

    public function eliminar(int $id): void
    {
        $hotel = $this->obtener($id);
        $this->hoteles->delete($hotel);
    }

    /** Regla: no deben existir hoteles repetidos (por NIT, o por nombre+ciudad). */
    private function validarDuplicados(string $nit, string $nombre, int $ciudadId, ?int $excludeId = null): void
    {
        if ($this->hoteles->existsByNit($nit, $excludeId)) {
            throw new BusinessRuleException("Ya existe un hotel registrado con el NIT {$nit}.", 409);
        }

        if ($this->hoteles->existsByNombreCiudad($nombre, $ciudadId, $excludeId)) {
            throw new BusinessRuleException('Ya existe un hotel con ese nombre en la misma ciudad.', 409);
        }
    }
}
