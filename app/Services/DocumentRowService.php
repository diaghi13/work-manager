<?php

namespace App\Services;

use App\Helpers\MoneyHelper;
use Pelmered\FilamentMoneyField\Forms\Components\MoneyInput;
use Pelmered\FilamentMoneyField\MoneyFormatter;

class DocumentRowService
{
    public function __construct()
    {
    }

    public static function calculateTotals($netPrice, $quantity): float
    {
        return $netPrice * $quantity;
    }
}
