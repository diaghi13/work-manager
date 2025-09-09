<?php

namespace App\Livewire;

use App\Filament\App\Resources\WorkDayResource;
use App\Models\WorkDay;
use App\Models\WorkDayTime;
use Filament\Forms;
use Filament\Widgets\Widget;
use FontLib\Table\Type\name;
use Illuminate\Database\Eloquent\Model;
use Livewire\Form;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;

class CalendarWidget extends FullCalendarWidget
{
    public Model|string|null $model = WorkDayTime::class;

    public function config(): array
    {
        return [
            'defaultView' => 'dayGridMonth',
            'headerToolbar' => [
                'left' => 'prev,next today',
                'center' => 'title',
                'right' => 'dayGridYear,dayGridMonth,timeGridWeek,timeGridDay',
            ],
            'views' => [
                'dayGridMonth' => [
                    'buttonText' => __('app.full_calendar.month'),
                    'titleFormat' => ['year', 'month'],
                ],
                'timeGridWeek' => [
                    'buttonText' => __('app.full_calendar.week'),
                    'titleFormat' => ['year', 'month', 'day'],
                ],
                'timeGridDay' => [
                    'buttonText' => __('app.full_calendar.day'),
                    'titleFormat' => ['year', 'month', 'day'],
                ],
            ],
            'firstDay' => 1,
            'locale' => app()->getLocale(),
            'navLinks' => true,
            'selectable' => true,
            'selectMirror' => true,
            'dayMaxEvents' => true,
            'weekNumbers' => false,
            'weekNumberCalculation' => 'ISO',
        ];
    }

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
                    //'allDay' => false,
                    'color' => $workDayTime->work_day->type === 'Regular' ? 'blue' : ($workDayTime->work_day->type === 'Overtime' ? 'red' : 'green'),
//                    'url' => WorkDayResource::getUrl(name: 'edit', parameters: ['record' => $workDayTime->work_day_id]),
//                    'shouldOpenUrlInNewTab' => true
                ];
            })->all();
    }

    public function getFormSchema(): array
    {
        return [
            Forms\Components\Select::make('work_day_id')
                ->label('Cantiere')
                ->relationship('work_day.worksite', 'name')
                ->searchable()
                ->required(),

            Forms\Components\Grid::make()
                ->schema([
                    Forms\Components\DateTimePicker::make('start_datetime'),

                    Forms\Components\DateTimePicker::make('end_datetime'),
                ]),

            // Recupera la descrizione del work day associato
            Forms\Components\Textarea::make('work_day_description')
                ->label('Descrizione Giornata Lavorativa')
                ->default(fn (callable $get) => optional(\App\Models\WorkDay::find($get('work_day_id')))->description)
                ->disabled()
                ->columnSpanFull(),

            Forms\Components\Textarea::make('notes')->label('Note')->columnSpanFull(),
        ];
    }
}
