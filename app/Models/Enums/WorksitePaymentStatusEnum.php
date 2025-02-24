<?php

namespace App\Models\Enums;

use Filament\Support\Contracts\HasLabel;

enum WorksitePaymentStatusEnum: int implements HasLabel
{
    case NOT_PAID = 0;
    case PENDING = 1;
    case PARTIALLY_PAID = 2;
    case PAID = 3;

    public function getLabel(): string
    {
        return match ($this) {
            self::NOT_PAID => 'Not Paid',
            self::PENDING => 'Pending',
            self::PARTIALLY_PAID => 'Partially Paid',
            self::PAID => 'Paid',
        };
    }
}
