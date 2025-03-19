<?php

namespace App\Filament\OutgoingResource\Pages;

use App\Filament\App\Resources\OutgoingResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewOutgoing extends ViewRecord
{
    protected static string $resource = OutgoingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
