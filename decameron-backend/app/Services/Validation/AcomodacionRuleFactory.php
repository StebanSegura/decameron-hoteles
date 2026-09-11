<?php

namespace App\Services\Validation;

use InvalidArgumentException;

/** Factory Method: resuelve la estrategia según el nombre del tipo de habitación. */
class AcomodacionRuleFactory
{
    public static function crear(string $tipoHabitacionNombre): AcomodacionRuleInterface
    {
        return match (strtoupper(trim($tipoHabitacionNombre))) {
            'ESTANDAR', 'ESTÁNDAR' => new EstandarAcomodacionRule(),
            'JUNIOR' => new JuniorAcomodacionRule(),
            'SUITE' => new SuiteAcomodacionRule(),
            default => throw new InvalidArgumentException(
                "No existe una regla de acomodación para el tipo de habitación '{$tipoHabitacionNombre}'."
            ),
        };
    }
}
