<?php

namespace App\Models\Enums;

use Filament\Support\Contracts\HasLabel;

enum WorkDayTypeEnum: string implements HasLabel
{
    case WORK = 'work';
    case TRAVEL = 'travel';
    case OFF = 'off';
    case OTHER = 'other';

    public function getLabel(): string
    {
        return match ($this) {
            self::WORK => 'Lavorativo',
            self::TRAVEL => 'Viaggio',
            self::OFF => 'Giorno libero',
            self::OTHER => 'Altro',
        };
    }
}
