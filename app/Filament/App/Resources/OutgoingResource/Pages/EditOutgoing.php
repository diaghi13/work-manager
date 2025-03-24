<?php

namespace App\Filament\App\Resources\OutgoingResource\Pages;

use App\Filament\App\Resources\OutgoingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOutgoing extends EditRecord
{
    protected static string $resource = OutgoingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
