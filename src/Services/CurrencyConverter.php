<?php

namespace Services;

use Enums\Currency;
use Exception;

class CurrencyConverter
{
    private const API_URL = 'https://open.er-api.com/v6/latest/USD';
    private const CACHE_TTL = 43200; // 12h

    public static function convert(float $amount, Currency $from, Currency $to): float
    {
        if ($from === $to) {
            return $amount;
        }

        $rates = self::getRates();

        $rateFrom = $rates[$from->code()] ?? 1.0;
        $rateTo = $rates[$to->code()] ?? 1.0;

        $amountInUsd = $amount / $rateFrom;

        return $amountInUsd * $rateTo;
    }

    private static function getRates(): array
    {
        $cacheFile = sys_get_temp_dir() . '/subtracker_rates_cache.json';

        if (file_exists($cacheFile) && (time() - filemtime($cacheFile)) < self::CACHE_TTL) {
            $cacheContent = file_get_contents($cacheFile);
            $data = json_decode($cacheContent, true);

            if (isset($data['rates'])) {
                return $data['rates'];
            }
        }

        try {
            $context = stream_context_create([
                'http' => [
                    'timeout' => 3
                ]
            ]);

            $response = file_get_contents(self::API_URL, false, $context);

            if ($response !== false) {
                $data = json_decode($response, true);
                if (isset($data['rates'])) {
                    file_put_contents($cacheFile, $response);
                    return $data['rates'];
                }
            }
        } catch (Exception $e) {
            if (file_exists($cacheFile)) {
                $cacheContent = file_get_contents($cacheFile);
                $data = json_decode($cacheContent, true);
                if (isset($data['rates'])) {
                    return $data['rates'];
                }
            }
        }

        return [
            'USD' => 1.0,
            'EUR' => 0.92,
            'PLN' => 4.00
        ];
    }
}