<?php

namespace App\Livewire;

use App\Models\WorkDay;
use Filament\Widgets\Widget;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;

class CalendarWidget extends FullCalendarWidget
{
    public function fetchEvents(array $info): array
    {
        return WorkDay::with(['worksite'])
            ->get()
            ->map(function (WorkDay $workDay) {
                return [
                    'id' => $workDay->id,
                    'title' => $workDay->worksite->name,
                    'start' => $workDay->date,
                    'allDay' => true,
                ];
            })->all();
    }
}
