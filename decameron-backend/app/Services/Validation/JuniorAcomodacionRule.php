<?php

namespace App\Services\Validation;

/** JUNIOR admite acomodación Triple o Cuádruple. */
class JuniorAcomodacionRule extends AbstractAcomodacionRule
{
    public function acomodacionesPermitidas(): array
    {
        return ['TRIPLE', 'CUADRUPLE'];
    }
}
