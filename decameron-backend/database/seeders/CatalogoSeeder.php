<?php

namespace Database\Seeders;

use App\Models\Acomodacion;
use App\Models\Ciudad;
use App\Models\TipoHabitacion;
use Illuminate\Database\Seeder;

/**
 * Catálogos base. El enunciado indica que estos datos NO requieren
 * administración (no hay CRUD ni pantallas para editarlos), por eso
 * viven únicamente como datos semilla.
 */
class CatalogoSeeder extends Seeder
{
    public function run(): void
    {
        $ciudades = [
            'CARTAGENA', 'SAN ANDRÉS', 'SANTA MARTA', 'BARRANQUILLA',
            'MEDELLÍN', 'BOGOTÁ', 'CALI', 'TOLÚ', 'GIRARDOT', 'PEREIRA',
        ];
        foreach ($ciudades as $nombre) {
            Ciudad::firstOrCreate(['nombre' => $nombre]);
        }

        $tipos = ['ESTANDAR', 'JUNIOR', 'SUITE'];
        foreach ($tipos as $nombre) {
            TipoHabitacion::firstOrCreate(['nombre' => $nombre]);
        }

        $acomodaciones = ['SENCILLA', 'DOBLE', 'TRIPLE', 'CUADRUPLE'];
        foreach ($acomodaciones as $nombre) {
            Acomodacion::firstOrCreate(['nombre' => $nombre]);
        }

        // Reglas de negocio: tipo de habitación -> acomodaciones permitidas.
        $reglas = [
            'ESTANDAR' => ['SENCILLA', 'DOBLE'],
            'JUNIOR' => ['TRIPLE', 'CUADRUPLE'],
            'SUITE' => ['SENCILLA', 'DOBLE', 'TRIPLE'],
        ];

        foreach ($reglas as $tipoNombre => $acomodacionesPermitidas) {
            $tipo = TipoHabitacion::where('nombre', $tipoNombre)->firstOrFail();
            $ids = Acomodacion::whereIn('nombre', $acomodacionesPermitidas)->pluck('id');
            $tipo->acomodacionesPermitidas()->syncWithoutDetaching($ids);
        }
    }
}
