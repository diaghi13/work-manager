<?php

namespace App\Filament\App\Resources\WorkDayResource\Pages;

use App\Filament\App\Resources\WorkDayResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\MaxWidth;

class ListWorkDays extends ListRecords
{
    protected static string $resource = WorkDayResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    /**
     * @return MaxWidth
     */
    public function getMaxContentWidth(): MaxWidth
    {
        return MaxWidth::Full;
    }
}
