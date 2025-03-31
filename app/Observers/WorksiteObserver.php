<?php

namespace App\Observers;

use App\Models\Enums\WorksiteStatusEnum;
use App\Models\Worksite;
use Carbon\Carbon;

class WorksiteObserver
{
    public function retrieved(Worksite $worksite): void
    {
        if ($worksite->status === WorksiteStatusEnum::ACCEPTED) {
            if (Carbon::now()->betweenIncluded($worksite->start_date, $worksite->end_date)) {
                $worksite->update(['status' => WorksiteStatusEnum::IN_PROGRESS]);
            }

            if (Carbon::now()->greaterThanOrEqualTo($worksite->start_date) && Carbon::now()->greaterThan($worksite->end_date)) {
                $worksite->update(['status' => WorksiteStatusEnum::COMPLETED]);
            }
        }

        if ($worksite->status === WorksiteStatusEnum::IN_PROGRESS) {
            if (Carbon::now()->greaterThan($worksite->end_date)) {
                $worksite->update(['status' => WorksiteStatusEnum::COMPLETED]);
            }
        }
    }

    /**
     * Handle the Worksite "created" event.
     */
    public function created(Worksite $worksite): void
    {
        //
    }

    /**
     * Handle the Worksite "updated" event.
     */
    public function updated(Worksite $worksite): void
    {
        //
    }

    /**
     * Handle the Worksite "deleted" event.
     */
    public function deleted(Worksite $worksite): void
    {
        //
    }

    /**
     * Handle the Worksite "restored" event.
     */
    public function restored(Worksite $worksite): void
    {
        //
    }

    /**
     * Handle the Worksite "force deleted" event.
     */
    public function forceDeleted(Worksite $worksite): void
    {
        //
    }
}
