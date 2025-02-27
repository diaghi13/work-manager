<?php

namespace App\Services;

class DocumentService
{
    public function __construct()
    {
    }

    public function calculateTotals($netPrice, $vatPercentage, $quantity): array
    {
        $total_net = $netPrice * $quantity;
        $total_vat = $total_net * $vatPercentage / 100;
        $total = $total_net + $total_vat;

        return [
            'total_net' => $total_net,
            'total_vat' => $total_vat,
            'total' => $total,
        ];
    }
}
