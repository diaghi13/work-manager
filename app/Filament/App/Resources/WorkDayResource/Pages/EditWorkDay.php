<?php

namespace App\Filament\App\Resources\WorkDayResource\Pages;

use App\Filament\App\Resources\WorkDayResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWorkDay extends EditRecord
{
    protected static string $resource = WorkDayResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

//    public function getRedirectUrl(): ?string
//    {
//        return WorkDayResource::getUrl('index');
//    }
}
