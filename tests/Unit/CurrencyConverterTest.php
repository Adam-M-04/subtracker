<?php

namespace Tests\Unit;

use Enums\Currency;
use PHPUnit\Framework\TestCase;
use Services\CurrencyConverter;

class CurrencyConverterTest extends TestCase
{
    public function testConvertReturnsSameAmountForSameCurrency(): void
    {
        $amount = 123.45;
        $result = CurrencyConverter::convert($amount, Currency::USD, Currency::USD);

        $this->assertSame($amount, $result);
    }

    public function testConvertReturnsPositiveAmountForDifferentCurrencies(): void
    {
        $result = CurrencyConverter::convert(10.0, Currency::USD, Currency::EUR);

        $this->assertGreaterThan(0, $result);
    }
}

