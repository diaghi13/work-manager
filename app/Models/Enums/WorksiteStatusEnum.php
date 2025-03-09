<?php

namespace App\Models\Enums;

use Filament\Support\Contracts\HasLabel;

enum WorksiteStatusEnum: string implements HasLabel
{
    case ACTIVE = 'active';
    case REJECTED = 'rejected';
    case PENDING = 'pending';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';
    case ACCEPTED = 'accepted';
    case IN_PROGRESS = 'in_progress';
    case INCOMING = 'incoming';

    public function getLabel(): string
    {
        return match ($this) {
            self::ACTIVE => __('app.status_type.active'),
            self::REJECTED => __('app.status_type.rejected'),
            self::PENDING => __('app.status_type.pending'),
            self::COMPLETED => __('app.status_type.completed'),
            self::CANCELLED => __('app.status_type.cancelled'),
            self::ACCEPTED => __('app.status_type.accepted'),
            self::IN_PROGRESS => __('app.status_type.in_progress'),
            self::INCOMING => __('app.status_type.incoming'),
        };
    }
}
