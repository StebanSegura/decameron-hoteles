<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreConfiguracionRequest;
use App\Http\Requests\UpdateConfiguracionRequest;
use App\Http\Resources\HotelConfiguracionResource;
use App\Services\ConfiguracionService;
use Illuminate\Http\JsonResponse;

class ConfiguracionController extends Controller
{
    public function __construct(private ConfiguracionService $service)
    {
    }

    public function index(int $hotelId): JsonResponse
    {
        return $this->ok(HotelConfiguracionResource::collection($this->service->listarPorHotel($hotelId)));
    }

    public function store(StoreConfiguracionRequest $request, int $hotelId): JsonResponse
    {
        $config = $this->service->crear($hotelId, $request->validated());

        return $this->ok(new HotelConfiguracionResource($config), 201);
    }

    public function update(UpdateConfiguracionRequest $request, int $hotelId, int $id): JsonResponse
    {
        $config = $this->service->actualizar($hotelId, $id, $request->validated());

        return $this->ok(new HotelConfiguracionResource($config));
    }

    public function destroy(int $hotelId, int $id): JsonResponse
    {
        $this->service->eliminar($hotelId, $id);

        return $this->ok(['message' => 'Configuración eliminada correctamente.']);
    }

    private function ok($data, int $status = 200): JsonResponse
    {
        return response()->json(['success' => true, 'data' => $data], $status);
    }
}
