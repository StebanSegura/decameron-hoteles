<?php

namespace App\Services;

use App\Exceptions\BusinessRuleException;
use App\Models\HotelConfiguracion;
use App\Repositories\CatalogoRepositoryInterface;
use App\Repositories\ConfiguracionRepositoryInterface;
use App\Repositories\HotelRepositoryInterface;
use App\Services\Validation\AcomodacionRuleFactory;

class ConfiguracionService
{
    public function __construct(
        private ConfiguracionRepositoryInterface $configuraciones,
        private HotelRepositoryInterface $hoteles,
        private CatalogoRepositoryInterface $catalogo,
    ) {
    }

    public function listarPorHotel(int $hotelId)
    {
        $this->obtenerHotelOFallar($hotelId);

        return $this->configuraciones->byHotel($hotelId);
    }

    public function crear(int $hotelId, array $data): HotelConfiguracion
    {
        $hotel = $this->obtenerHotelOFallar($hotelId);
        $data['hotel_id'] = $hotelId;

        $tipoNombre = $this->catalogo->nombreTipoHabitacion($data['tipo_habitacion_id']);
        $this->validarCombinacionPermitida($tipoNombre, $data['acomodacion_id']);
        $this->validarNoDuplicado($hotelId, $data['tipo_habitacion_id'], $data['acomodacion_id']);
        $this->validarCapacidadMaxima($hotel->numero_habitaciones, $hotelId, $data['cantidad']);

        return $this->configuraciones->create($data);
    }

    public function actualizar(int $hotelId, int $configId, array $data): HotelConfiguracion
    {
        $hotel = $this->obtenerHotelOFallar($hotelId);
        $config = $this->configuraciones->find($configId);

        if (! $config || $config->hotel_id !== $hotelId) {
            throw new BusinessRuleException('La configuración indicada no existe para este hotel.', 404);
        }

        $tipoNombre = $this->catalogo->nombreTipoHabitacion($data['tipo_habitacion_id']);
        $this->validarCombinacionPermitida($tipoNombre, $data['acomodacion_id']);
        $this->validarNoDuplicado($hotelId, $data['tipo_habitacion_id'], $data['acomodacion_id'], $configId);
        $this->validarCapacidadMaxima($hotel->numero_habitaciones, $hotelId, $data['cantidad'], $configId);

        return $this->configuraciones->update($config, $data);
    }

    public function eliminar(int $hotelId, int $configId): void
    {
        $this->obtenerHotelOFallar($hotelId);
        $config = $this->configuraciones->find($configId);

        if (! $config || $config->hotel_id !== $hotelId) {
            throw new BusinessRuleException('La configuración indicada no existe para este hotel.', 404);
        }

        $this->configuraciones->delete($config);
    }

    private function obtenerHotelOFallar(int $hotelId)
    {
        $hotel = $this->hoteles->find($hotelId);

        if (! $hotel) {
            throw new BusinessRuleException("El hotel con id {$hotelId} no existe.", 404);
        }

        return $hotel;
    }

    private function validarCombinacionPermitida(?string $tipoNombre, int $acomodacionId): void
    {
        if ($tipoNombre === null) {
            throw new BusinessRuleException('El tipo de habitación no existe.', 422, ['tipo_habitacion_id' => 'Inválido']);
        }

        $acomodacionNombre = $this->catalogo->nombreAcomodacion($acomodacionId);
        if ($acomodacionNombre === null) {
            throw new BusinessRuleException('La acomodación no existe.', 422, ['acomodacion_id' => 'Inválida']);
        }

        $regla = AcomodacionRuleFactory::crear($tipoNombre);

        if (! $regla->permite($acomodacionNombre)) {
            $permitidas = implode(', ', $regla->acomodacionesPermitidas());
            throw new BusinessRuleException(
                "La acomodación '{$acomodacionNombre}' no es válida para el tipo '{$tipoNombre}'. Acomodaciones permitidas: {$permitidas}.",
                422,
                ['acomodacion_id' => "Debe ser una de: {$permitidas}"]
            );
        }
    }

    private function validarNoDuplicado(int $hotelId, int $tipoHabitacionId, int $acomodacionId, ?int $excludeId = null): void
    {
        if ($this->configuraciones->existsCombinacion($hotelId, $tipoHabitacionId, $acomodacionId, $excludeId)) {
            throw new BusinessRuleException('Ya existe una configuración con ese tipo de habitación y acomodación para este hotel.', 409);
        }
    }

    private function validarCapacidadMaxima(int $maxHabitaciones, int $hotelId, int $cantidadNueva, ?int $excludeId = null): void
    {
        $totalActual = $this->configuraciones->totalConfigurado($hotelId, $excludeId);
        $totalConNueva = $totalActual + $cantidadNueva;

        if ($totalConNueva > $maxHabitaciones) {
            $disponibles = max(0, $maxHabitaciones - $totalActual);
            throw new BusinessRuleException(
                "La cantidad de habitaciones configuradas ({$totalConNueva}) supera el máximo del hotel ({$maxHabitaciones}). Disponibles: {$disponibles}.",
                422,
                ['cantidad' => "Máximo disponible para asignar: {$disponibles}"]
            );
        }
    }
}
