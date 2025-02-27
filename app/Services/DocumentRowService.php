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

    public static function calculateTotals($netPrice, $vatPercentage, $quantity): array
    {
        if (!$netPrice || !$vatPercentage || !$quantity) {
            return [
                'total_net' => 0,
                'total_vat' => 0,
                'total' => 0,
            ];
        }
        //$total_net = MoneyHelper::formatToInteger($netPrice) * (int)$quantity;
        $total_net = MoneyHelper::formatToInteger($netPrice) * (int)$quantity;
        $total_vat = (int)round($total_net * $vatPercentage / 100);
        $total = $total_net + $total_vat;

        return [
            'total_net' => MoneyHelper::formatToUI($total_net),
            'total_vat' => $total_vat,
            'total' => $total,
        ];
    }
}
