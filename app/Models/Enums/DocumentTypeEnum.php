<?php

namespace App\Models\Enums;

use Filament\Support\Contracts\HasLabel;

enum DocumentTypeEnum: string implements HasLabel
{
    case INVOICE = 'invoice';
    case RECEIPT = 'receipt';
    case QUOTE = 'quote';

    public function getLabel(): string
    {
        return match ($this) {
            self::INVOICE => 'Invoice',
            self::RECEIPT => 'Receipt',
            self::QUOTE => 'Quote',
        };
    }
}
