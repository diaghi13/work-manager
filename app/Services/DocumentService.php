<?php

namespace App\Services;

use App\Models\Vat;

class DocumentService
{
    public function __construct()
    {
    }

    public function calculateTotals(array $documentRows): array
    {
        $totalNetPrice = 0;
        $totalVatPrice = 0;
        $totalGrossPrice = 0;
        $totalItems = 0;

        foreach ($documentRows as $row) {
            if (!$row['vat_id'] || !$row['net_price'] || !$row['quantity']) {
                continue;
            }

            $vat = Vat::find($row['vat_id']);

            $netPrice = $row['net_price'] * $row['quantity'];
            $vatPrice = $row['net_price'] * ($vat->value / 100) * $row['quantity'];
            $grossPrice = $netPrice + $vatPrice;

            $totalNetPrice += $netPrice;
            $totalVatPrice += $vatPrice;
            $totalGrossPrice += $grossPrice;

            $totalItems += $row['quantity'];
        }

        return [
            'net_price' => round($totalNetPrice, 2),
            'vat_price' => round($totalVatPrice, 2),
            'gross_price' => round($totalGrossPrice, 2),
            'total_items' => $totalItems,
        ];
    }
}
