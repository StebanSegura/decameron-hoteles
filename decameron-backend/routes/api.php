<?php

use App\Http\Controllers\Api\CatalogoController;
use App\Http\Controllers\Api\ConfiguracionController;
use App\Http\Controllers\Api\HotelController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes (Hoteles Decameron)
|--------------------------------------------------------------------------
| Todas viven bajo /api gracias al RouteServiceProvider por defecto de
| Laravel. El frontend (React) consume exclusivamente estas rutas.
*/

Route::get('/health', fn () => response()->json(['success' => true, 'data' => ['status' => 'ok']]));

// Catálogos: solo lectura, no requieren administración.
Route::get('/ciudades', [CatalogoController::class, 'ciudades']);
Route::get('/tipos-habitacion', [CatalogoController::class, 'tiposHabitacion']);
Route::get('/acomodaciones', [CatalogoController::class, 'acomodaciones']);
Route::get('/tipos-habitacion/{tipoId}/acomodaciones', [CatalogoController::class, 'acomodacionesPermitidas']);

// Hoteles
Route::apiResource('hoteles', HotelController::class);

// Configuraciones de habitación por hotel
Route::get('/hoteles/{hotelId}/configuraciones', [ConfiguracionController::class, 'index']);
Route::post('/hoteles/{hotelId}/configuraciones', [ConfiguracionController::class, 'store']);
Route::put('/hoteles/{hotelId}/configuraciones/{id}', [ConfiguracionController::class, 'update']);
Route::delete('/hoteles/{hotelId}/configuraciones/{id}', [ConfiguracionController::class, 'destroy']);
