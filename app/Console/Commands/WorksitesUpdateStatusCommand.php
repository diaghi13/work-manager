<?php

namespace App\Console\Commands;

use App\Exceptions\Tenant\TenantDatabaseNotProvidedException;
use Illuminate\Console\Command;

class WorksitesUpdateStatusCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'worksites:update-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the status of worksites based on their start and end dates';

    /**
     * Execute the console command.
     * @throws TenantDatabaseNotProvidedException
     */
    public function handle()
    {
        $this->info('Updating worksite status...');

        $tenants = \App\Models\Tenant::query()
            ->select('id')
            ->get();

        foreach ($tenants as $tenant) {
            \App\Models\Tenant::switch(false, $tenant->id);

            $this->info('Connected to tenant: ' . $tenant->id);

            try {
                \Illuminate\Support\Facades\DB::connection('dynamic')->getPdo();
            } catch (\Exception $e) {
                $this->error('Error connecting to tenant: ' . $tenant->id . ' - ' . $e->getMessage());
                $this->error('Skipping tenant: ' . $tenant->id);
                $this->newLine();
                continue;
            }

            \Illuminate\Support\Facades\DB::transaction(function () use ($tenant) {
                $this->info('Transaction started for tenant: ' . $tenant->id);

                // Update the worksite status
                updateWorksiteStatus($tenant);
            });

            $this->info('Worksite status updated for tenant: ' . $tenant->id);
            $this->newLine();
        }

        $this->info('Worksite status update completed for all tenants.');

        return 0;
    }
}

function updateWorksiteStatus(mixed $tenant): void
{
    $worksites = \App\Models\Worksite::query()
        ->where('status', \App\Models\Enums\WorksiteStatusEnum::ACCEPTED)
        ->orWhere('status', \App\Models\Enums\WorksiteStatusEnum::IN_PROGRESS)
        ->orWhere('status', \App\Models\Enums\WorksiteStatusEnum::ACTIVE)
        //->where('end_date', '<=', now()->toDateString())
        ->get();

    foreach ($worksites as $worksite) {

        switch ($worksite->status->value) {
            case \App\Models\Enums\WorksiteStatusEnum::ACCEPTED->value:
                if (now()->betweenIncluded($worksite->start_date, $worksite->end_date)) {
                    $worksite->update(['status' => \App\Models\Enums\WorksiteStatusEnum::IN_PROGRESS]);
                }
                break;

            case \App\Models\Enums\WorksiteStatusEnum::IN_PROGRESS->value
                || \App\Models\Enums\WorksiteStatusEnum::ACTIVE->value:
                if (now()->greaterThan($worksite->end_date)) {
                    $worksite->update(['status' => \App\Models\Enums\WorksiteStatusEnum::COMPLETED]);
                }
                break;
        }
    }
}
