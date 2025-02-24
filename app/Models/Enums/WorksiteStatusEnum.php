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
            self::ACTIVE => 'Active',
            self::REJECTED => 'Rejected',
            self::PENDING => 'Pending',
            self::COMPLETED => 'Completed',
            self::CANCELLED => 'Cancelled',
            self::ACCEPTED => 'Accepted',
            self::IN_PROGRESS => 'In Progress',
            self::INCOMING => 'Incoming',
        };
    }
}
