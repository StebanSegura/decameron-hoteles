<?php

namespace App\Services\Validation;

/** ESTANDAR admite acomodación Sencilla o Doble. */
class EstandarAcomodacionRule extends AbstractAcomodacionRule
{
    public function acomodacionesPermitidas(): array
    {
        return ['SENCILLA', 'DOBLE'];
    }
}
