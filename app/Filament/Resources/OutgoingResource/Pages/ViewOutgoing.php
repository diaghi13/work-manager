<?php

namespace App\Filament\Resources\OutgoingResource\Pages;

use App\Filament\Resources\OutgoingResource;
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
