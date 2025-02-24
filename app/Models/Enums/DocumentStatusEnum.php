<?php

namespace App\Models\Enums;

use Filament\Support\Contracts\HasLabel;

enum DocumentStatusEnum: string implements  HasLabel
{
    case DRAFT = 'draft';
    case SENT = 'sent';
    case PAID = 'paid';
    case CANCELLED = 'cancelled';

    public function getLabel(): string
    {
        return match ($this) {
            self::DRAFT => 'Draft',
            self::SENT => 'Sent',
            self::PAID => 'Paid',
            self::CANCELLED => 'Cancelled',
        };
    }
}
