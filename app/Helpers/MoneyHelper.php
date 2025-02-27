<?php

namespace App\Helpers;

use Brick\Money\Money;

class MoneyHelper
{
    public static function format($value, $decimals = 2, $dec_point = '.', $thousands_sep = ','): string
    {
        return number_format($value, $decimals, $dec_point, $thousands_sep);
    }

    public static function formatWithCurrency($value, $currency = '€', $decimals = 2, $dec_point = '.', $thousands_sep = ','): string
    {
        return $currency . ' ' . self::format($value, $decimals, $dec_point, $thousands_sep);
    }

    public static function formatWithCurrencyAndSign($value, $currency = '€', $decimals = 2, $dec_point = '.', $thousands_sep = ','): string
    {
        return ($value < 0 ? '-' : '+') . ' ' . self::formatWithCurrency(abs($value), $currency, $decimals, $dec_point, $thousands_sep);
    }

    public static function formatWithCurrencyAndSignAndColor($value, $currency = '€', $decimals = 2, $dec_point = '.', $thousands_sep = ','): string
    {
        return ($value < 0 ? '<span class="text-red-500">-</span>' : '<span class="text-green-500">+</span>') . ' ' . self::formatWithCurrency(abs($value), $currency, $decimals, $dec_point, $thousands_sep);
    }

    public static function formatWithCurrencyAndColor($value, $currency = '€', $decimals = 2, $dec_point = '.', $thousands_sep = ','): string
    {
        return ($value < 0 ? '<span class="text-red-500">-</span>' : '<span class="text-green-500">+</span>') . ' ' . self::formatWithCurrency(abs($value), $currency, $decimals, $dec_point, $thousands_sep);
    }

    public static function formatWithCurrencyAndColorAndSign($value, $currency = '€', $decimals = 2, $dec_point = '.', $thousands_sep = ','): string
    {
        return ($value < 0 ? '<span class="text-red-500">-</span>' : '<span class="text-green-500">+</span>') . ' ' . self::formatWithCurrency(abs($value), $currency, $decimals, $dec_point, $thousands_sep);
    }

    public static function formatToInteger($value): int
    {
        if (strpos($value, '.')) {
            $value = str_replace('.', '', $value);
        }

        if (strpos($value, ',')) {
            $value = str_replace(',', '', $value);
        }

        if (strpos($value, ' ')) {
            $value = str_replace(' ', '', $value);
        }

        return (int)$value;
    }

    public static function formatToFloat($value): float
    {
        if (strpos($value, ',')) {
            $value = str_replace(',', '.', $value);
        }

        return (float)$value;
    }

    public static function formatToUI($value): string
    {
        $decimals = env('MONEY_DECIMAL_DIGITS', 2);

        $value = Money::ofMinor($value, 'EUR')->getAmount()->to();

        dd($value);

        return self::format(sprintf('%.' . $decimals . 'f', $value), $decimals, ',', '.');
    }
}
