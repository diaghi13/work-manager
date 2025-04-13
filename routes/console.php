<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

function updateWorksiteStatus(mixed $tenant)
{
    $worksites = \App\Models\Worksite::query()
        ->where('status', \App\Models\Enums\WorksiteStatusEnum::ACCEPTED)
        ->orWhere('status', \App\Models\Enums\WorksiteStatusEnum::IN_PROGRESS)
        ->orWhere('status', \App\Models\Enums\WorksiteStatusEnum::ACTIVE)
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
}

\Illuminate\Support\Facades\Schedule::call(function () {
    \Illuminate\Support\Facades\Log::info('Updating worksite status...');

    $tenants = \App\Models\Tenant::query()
        ->select('id')
        ->get();

    foreach ($tenants as $tenant) {
        \App\Models\Tenant::switch(false, $tenant->id);

        \Illuminate\Support\Facades\Log::info('Connected to tenants: ' . $tenant->id);

        try {
            \Illuminate\Support\Facades\DB::connection('dynamic')->getPdo();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error connecting to tenant: ' . $tenant->id . ' - ' . $e->getMessage());
            continue;
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($tenant) {
            \Illuminate\Support\Facades\Log::info('Transaction started for tenant: ' . $tenant->id);

            // Update the worksite status
            updateWorksiteStatus($tenant);
        });

        \Illuminate\Support\Facades\Log::info('Worksite status updated for tenant: ' . $tenant->id);
    }

    \Illuminate\Support\Facades\Log::info('Worksite status update completed.');
})
    ->daily()
    ->timezone('Europe/Rome')
    ->name('update worksite status');
