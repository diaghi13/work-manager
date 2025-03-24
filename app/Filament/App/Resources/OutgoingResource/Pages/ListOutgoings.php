<?php

namespace App\Filament\App\Resources\OutgoingResource\Pages;

use App\Filament\App\Resources\OutgoingResource;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOutgoings extends ListRecords
{
    protected static string $resource = OutgoingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
