<?php

namespace App\Filament\App\Resources\WorksiteResource\Pages;

use App\Filament\App\Resources\WorksiteResource;
use Filament\Resources\Pages\ViewRecord;

class ViewWorksite extends ViewRecord
{
    protected static string $resource = WorksiteResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            WorksiteResource\Widgets\WorkDaysOverview::class,
        ];
    }
}
