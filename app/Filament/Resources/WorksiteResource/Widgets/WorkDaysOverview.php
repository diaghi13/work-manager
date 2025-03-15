<?php

namespace App\Filament\Resources\WorksiteResource\Widgets;

use App\Models\Worksite;
use Filament\Widgets\ChartWidget;
use Illuminate\Database\Eloquent\Model;

class WorkDaysOverview extends ChartWidget
{
    protected static ?string $heading = 'Chart';

    public ?Worksite $record = null;

    protected function getData(): array
    {
        $workdays = $this->record->work_days;

        return [
            'datasets' => [
                [
                    'label' => 'Blog posts created',
                    'data' => $workdays->map(fn (Model $workday) => $workday->total_hours)->toArray(),
                    'borderRadius' => 20,
                    'maxBarThickness' => 30,
                ],
            ],
            'labels' => $workdays->map(fn (Model $workday) => $workday->date)->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
