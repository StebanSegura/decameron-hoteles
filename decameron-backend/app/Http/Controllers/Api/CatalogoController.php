<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\CatalogoRepositoryInterface;
use Illuminate\Http\JsonResponse;

class CatalogoController extends Controller
{
    public function __construct(private CatalogoRepositoryInterface $catalogo)
    {
    }

    public function ciudades(): JsonResponse
    {
        return $this->ok($this->catalogo->ciudades());
    }

    public function tiposHabitacion(): JsonResponse
    {
        return $this->ok($this->catalogo->tiposHabitacion());
    }

    public function acomodaciones(): JsonResponse
    {
        return $this->ok($this->catalogo->acomodaciones());
    }

    public function acomodacionesPermitidas(int $tipoId): JsonResponse
    {
        return $this->ok($this->catalogo->acomodacionesPermitidas($tipoId));
    }

    private function ok($data): JsonResponse
    {
        return response()->json(['success' => true, 'data' => $data]);
    }
}
