<?php

namespace App\Observers;

use App\Models\WorkDayTime;

class WorkDayTimeObserver
{
    /**
     * Handle the WorkDayTime "created" event.
     */
    public function created(WorkDayTime $workDayTime): void
    {
        $totalHours = 0;

        foreach ($workDayTime->work_day->work_day_datetimes as $workDayDateTime) {
            $totalHours += $workDayDateTime->total_hours;
        }

        $workDayTime->work_day->update(['total_hours' => round($totalHours, 2)]);

        $this->calculateWorkDayDetails($workDayTime);
    }

    /**
     * Handle the WorkDayTime "updated" event.
     */
    public function updated(WorkDayTime $workDayTime): void
    {

        $totalHours = 0;

        foreach ($workDayTime->work_day->work_day_datetimes as $workDayDateTime) {
            $totalHours += $workDayDateTime->total_hours;
        }

        $workDayTime->work_day->update(['total_hours' => round($totalHours, 2)]);

        $this->calculateWorkDayDetails($workDayTime);
    }

    /**
     * Handle the WorkDayTime "deleted" event.
     */
    public function deleted(WorkDayTime $workDayTime): void
    {
        $totalHours = 0;

        foreach ($workDayTime->work_day->work_day_datetimes as $workDayDateTime) {
            $totalHours += $workDayDateTime->total_hours;
        }

        $workDayTime->work_day->update(['total_hours' => $totalHours > 0 ?$totalHours : null]);

        $this->calculateWorkDayDetails($workDayTime);
    }

    /**
     * Handle the WorkDayTime "restored" event.
     */
    public function restored(WorkDayTime $workDayTime): void
    {
        //
    }

    /**
     * Handle the WorkDayTime "force deleted" event.
     */
    public function forceDeleted(WorkDayTime $workDayTime): void
    {
        //
    }

    public function calculateWorkDayDetails(WorkDayTime $workDayTime)
    {
        $workDay = $workDayTime->work_day;
        $workDay->refresh();

        $worksite = $workDay->worksite;

        $extra_time = 0;

        if ($workDay->total_hours > $worksite->daily_hours) {
            $extra_time = $workDay->total_hours - $worksite->daily_hours;
        }

        $extra_time_cost = $workDay->calculate_extra_time_cost
            ?  floor(($worksite->extra_time_cost * $extra_time) * 2) / 2
            : 0;
        $totalRemuneration = $workDay->daily_cost + $extra_time_cost;

        $workDay->update([
            'extra_time' => $extra_time,
            'extra_time_cost' => $extra_time_cost,
            'total_remuneration' => $totalRemuneration,
        ]);
    }
}
