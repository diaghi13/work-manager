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
            self::TECHNICIAN => __('app.job_type.technician'),
            self::TOUR => __('app.job_type.tour'),
            self::SERVICE => __('app.job_type.service'),
        };
    }
}
