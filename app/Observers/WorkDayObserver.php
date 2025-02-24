<?php

namespace App\Observers;

use App\Models\WorkDay;
use Carbon\Carbon;

class WorkDayObserver
{
    /**
     * Handle the WorkDay "created" event.
     */

    public function creating(WorkDay $workDay): void
    {
        //
    }

    public function created(WorkDay $workDay): void
    {
        //
    }

    /**
     * Handle the WorkDay "updated" event.
     */

    public function updating(WorkDay $workDay): void
    {
        $worksite = $workDay->worksite;

//        $extra_time_cost = $workDay->calculate_extra_time_cost
//            ?  floor(($worksite->extra_time_cost * $workDay->extra_time) * 2) / 2
//            : 0;
//        $total_remuneration = $workDay->daily_cost + $extra_time_cost;

        $extra_time_cost = floor(($worksite->extra_time_cost * $workDay->extra_time) * 2) / 2;
        $total_remuneration = $workDay->calculate_extra_time_cost
            ? $workDay->daily_cost + $extra_time_cost
            : $workDay->daily_cost;

        $workDay->extra_time_cost = $extra_time_cost;
        $workDay->total_remuneration = $total_remuneration;
    }

    /**
     * Handle the WorkDay "deleted" event.
     */
    public function deleted(WorkDay $workDay): void
    {
        //
    }

    /**
     * Handle the WorkDay "restored" event.
     */
    public function restored(WorkDay $workDay): void
    {
        //
    }

    /**
     * Handle the WorkDay "force deleted" event.
     */
    public function forceDeleted(WorkDay $workDay): void
    {
        //
    }

    /**
     * @param WorkDay $workDay
     * @return void
     */
    public function updateCalculatedFields(WorkDay $workDay): array
    {
        $totalDuration = (new Carbon($workDay->end_date_time))->diffInMinutes(new Carbon($workDay->start_date_time), true);
        $total_hours = $totalDuration / 60;

        $dailyHours = $workDay->customer->daily_hours;

        if ($total_hours > $dailyHours) {
            $extra_time = $total_hours - $dailyHours;
        } else {
            $extra_time = 0;
        }

        $extra_time_cost = $workDay->calculate_extra_cost ? $extra_time * $workDay->customer->extra_time_cost : 0;

        //$daily_cost = (float)$workDay->customer->daily_cost;

        $total_remuneration = $workDay->daily_cost + $extra_time_cost;
        $total_extra_cost = $workDay->travel_cost + $workDay->meal_cost + $workDay->extra_cost;

        return [
            'total_hours' => $total_hours,
            'extra_time' => $extra_time,
            'extra_time_cost' => $extra_time_cost,
            'daily_cost' => $workDay->daily_cost,
            'total_remuneration' => $total_remuneration,
            'total_extra_cost' => $total_extra_cost,
        ];
    }
}
