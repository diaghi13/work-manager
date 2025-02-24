<?php

namespace App\Models\Enums;

use Filament\Support\Contracts\HasLabel;

enum WorksiteTypeEnum: string implements HasLabel
{
    case TECHNICIAN = 'technician';
    case TOUR = 'tour';
    case SERVICE = 'service';

    public function getLabel(): string
    {
        return match ($this) {
            self::TECHNICIAN => 'Technician',
            self::TOUR => 'Tour',
            self::SERVICE => 'Service',
        };
    }
}
