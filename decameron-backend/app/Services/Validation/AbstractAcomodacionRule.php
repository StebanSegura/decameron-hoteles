<?php

namespace App\Services\Validation;

abstract class AbstractAcomodacionRule implements AcomodacionRuleInterface
{
    public function permite(string $acomodacionNombre): bool
    {
        return in_array(strtoupper(trim($acomodacionNombre)), $this->acomodacionesPermitidas(), true);
    }
}
