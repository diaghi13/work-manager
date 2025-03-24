<?php

namespace App\Filament\App\Resources\WorksiteResource\Pages;

use App\Filament\App\Resources\WorksiteResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWorksites extends ListRecords
{
    protected static string $resource = WorksiteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
