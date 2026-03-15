<?php

namespace Enums;

enum Currency: int
{
    case USD = 1;
    case EUR = 2;
    case PLN = 3;

    public function symbol(): string
    {
        return match($this) {
            self::USD => '$',
            self::EUR => '€',
            self::PLN => 'zł',
        };
    }

    public function code(): string
    {
        return match($this) {
            self::USD => 'USD',
            self::EUR => 'EUR',
            self::PLN => 'PLN',
        };
    }
}