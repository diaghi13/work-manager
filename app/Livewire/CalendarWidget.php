<?php

namespace App\Livewire;

use App\Models\WorkDay;
use App\Models\WorkDayTime;
use Filament\Widgets\Widget;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;

class CalendarWidget extends FullCalendarWidget
{
    public function fetchEvents(array $info): array
    {
        return WorkDayTime::with(['work_day' => ['worksite']])
            ->get()
            ->map(function (WorkDayTime $workDayTime) {
            return [
                'id' => $workDayTime->id,
                'title' => $workDayTime->work_day->worksite ? $workDayTime->work_day->worksite->name : 'No Worksite',
                'start' => $workDayTime->start_datetime->toIso8601String(),
                'end' => $workDayTime->end_datetime->toIso8601String(),
                'allDay' => false,
                'color' => $workDayTime->work_day->type === 'Regular' ? 'blue' : ($workDayTime->work_day->type === 'Overtime' ? 'red' : 'green'),
            ];
        })->all();
    }
}
