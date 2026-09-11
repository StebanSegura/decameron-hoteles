<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreHotelRequest;
use App\Http\Requests\UpdateHotelRequest;
use App\Http\Resources\HotelResource;
use App\Services\HotelService;
use Illuminate\Http\JsonResponse;

class HotelController extends Controller
{
    public function __construct(private HotelService $service)
    {
    }

    public function index(): JsonResponse
    {
        return $this->ok(HotelResource::collection($this->service->listar()));
    }

    public function show(int $id): JsonResponse
    {
        return $this->ok(new HotelResource($this->service->obtener($id)));
    }

    public function store(StoreHotelRequest $request): JsonResponse
    {
        $hotel = $this->service->crear($request->validated());

        return $this->ok(new HotelResource($hotel), 201);
    }

    public function update(UpdateHotelRequest $request, int $id): JsonResponse
    {
        $hotel = $this->service->actualizar($id, $request->validated());

        return $this->ok(new HotelResource($hotel));
    }

    public function destroy(int $id): JsonResponse
    {
        $this->service->eliminar($id);

        return $this->ok(['message' => 'Hotel eliminado correctamente.']);
    }

    private function ok($data, int $status = 200): JsonResponse
    {
        return response()->json(['success' => true, 'data' => $data], $status);
    }
}
