<?php

namespace App\Services\Validation;

interface AcomodacionRuleInterface
{
    /** @return string[] */
    public function acomodacionesPermitidas(): array;

    public function permite(string $acomodacionNombre): bool;
}
