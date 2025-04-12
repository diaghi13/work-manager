<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

\Illuminate\Support\Facades\Schedule::call(function () {
    $worksites = \App\Models\Worksite::query()
        ->where('status', '<>', \App\Models\Enums\WorksiteStatusEnum::ACCEPTED)
        ->orWhere('status', '<>', \App\Models\Enums\WorksiteStatusEnum::IN_PROGRESS)
        ->orWhere('status', '<>', \App\Models\Enums\WorksiteStatusEnum::ACTIVE)
        //->where('end_date', '<=', now()->toDateString())
        ->get();

    foreach ($worksites as $worksite) {
        switch ($worksite->status) {
            case \App\Models\Enums\WorksiteStatusEnum::ACCEPTED:
                if (now()->betweenIncluded($worksite->start_date, $worksite->end_date)) {
                    $worksite->update(['status' => \App\Models\Enums\WorksiteStatusEnum::IN_PROGRESS]);
                }
                break;

            case \App\Models\Enums\WorksiteStatusEnum::IN_PROGRESS
                || \App\Models\Enums\WorksiteStatusEnum::ACTIVE:
                if (now()->greaterThan($worksite->end_date)) {
                    $worksite->update(['status' => \App\Models\Enums\WorksiteStatusEnum::COMPLETED]);
                }
                break;
        }
    }
})
    ->daily()
    ->timezone('Europe/Rome')
    ->name('update worksite status');
