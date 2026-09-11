<?php

namespace Tests\Unit;

use App\Services\Validation\AcomodacionRuleFactory;
use App\Services\Validation\EstandarAcomodacionRule;
use App\Services\Validation\JuniorAcomodacionRule;
use App\Services\Validation\SuiteAcomodacionRule;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

/**
 * Prueba unitaria pura: no toca base de datos ni framework HTTP,
 * solo valida la lógica de negocio central de la prueba técnica.
 */
class AcomodacionRuleTest extends TestCase
{
    public function test_estandar_permite_sencilla_y_doble(): void
    {
        $regla = AcomodacionRuleFactory::crear('ESTANDAR');

        $this->assertInstanceOf(EstandarAcomodacionRule::class, $regla);
        $this->assertTrue($regla->permite('SENCILLA'));
        $this->assertTrue($regla->permite('DOBLE'));
        $this->assertFalse($regla->permite('TRIPLE'));
        $this->assertFalse($regla->permite('CUADRUPLE'));
    }

    public function test_junior_permite_triple_y_cuadruple(): void
    {
        $regla = AcomodacionRuleFactory::crear('JUNIOR');

        $this->assertInstanceOf(JuniorAcomodacionRule::class, $regla);
        $this->assertTrue($regla->permite('TRIPLE'));
        $this->assertTrue($regla->permite('CUADRUPLE'));
        $this->assertFalse($regla->permite('SENCILLA'));
        $this->assertFalse($regla->permite('DOBLE'));
    }

    public function test_suite_permite_sencilla_doble_y_triple(): void
    {
        $regla = AcomodacionRuleFactory::crear('SUITE');

        $this->assertInstanceOf(SuiteAcomodacionRule::class, $regla);
        $this->assertTrue($regla->permite('SENCILLA'));
        $this->assertTrue($regla->permite('DOBLE'));
        $this->assertTrue($regla->permite('TRIPLE'));
        $this->assertFalse($regla->permite('CUADRUPLE'));
    }

    public function test_la_comparacion_ignora_mayusculas_y_espacios(): void
    {
        $regla = AcomodacionRuleFactory::crear('estandar');

        $this->assertTrue($regla->permite(' sencilla '));
    }

    public function test_tipo_de_habitacion_desconocido_lanza_excepcion(): void
    {
        $this->expectException(InvalidArgumentException::class);

        AcomodacionRuleFactory::crear('PENTHOUSE');
    }
}
