<?php

namespace App\Services\Validation;

/** SUITE admite acomodación Sencilla, Doble o Triple. */
class SuiteAcomodacionRule extends AbstractAcomodacionRule
{
    public function acomodacionesPermitidas(): array
    {
        return ['SENCILLA', 'DOBLE', 'TRIPLE'];
    }
}
