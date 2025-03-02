<?php

namespace App\Models\Enums;

use Filament\Support\Contracts\HasLabel;

enum OutgoingTypeEnum: string implements HasLabel
{
    case TRAVEL = 'travel';
    case MEAL = 'meal';
    case OVERNIGHT = 'overnight';
    case FUEL = 'fuel';
    case EXTRA = 'extra';
    case OTHER = 'other';

    public function getLabel(): string
    {
        return match ($this) {
            self::TRAVEL => 'Viaggio',
            self::MEAL => 'Pasto',
            self::OVERNIGHT => 'Pernottamento',
            self::FUEL => 'Carburante',
            self::EXTRA => 'Extra',
            self::OTHER => 'Altro',
        };
    }
}
