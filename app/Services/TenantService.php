<?php

namespace App\Services;

use App\Jobs\ProcessTenantDatabaseCreationJob;
use App\Jobs\ProcessTenantMigrationJob;
use App\Jobs\ProcessTenantSeedJob;
use App\Models\Tenant;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;

class TenantService
{
    public function create()
    {
        $tenant = Tenant::create([
            'id' => $this->generateId(),
        ]);

        $this->addJsonData($tenant);

        $this->makeJobs($tenant);

        return $tenant;
    }

    /**
     * @return string
     */
    protected function generateUUID(): string
    {
        return \Ramsey\Uuid\Uuid::uuid4()->toString();
    }

    protected function generateId(): string
    {
        $prefix = config('tenancy.prefix');
        $suffix = config('tenancy.suffix');
        $tenantId = $this->generateUUID();

        return $prefix . $tenantId . $suffix;
    }

    protected function addJsonData($tenant): void
    {
        $tenant->syncOriginal();

        $tenant->data = [
            'id' => $tenant->id,
            'created_at' => $tenant->created_at,
            'updated_at' => $tenant->updated_at,
        ];

        $tenant->save();
    }

    protected function makeJobs($tenant): void
    {
        $seed = config('tenancy.with_seeding');
        $seedJob = $seed ? new ProcessTenantSeedJob($tenant->id) : null;

        $chain = Bus::chain([
            new ProcessTenantDatabaseCreationJob($tenant->id),
            new ProcessTenantMigrationJob($tenant->id),
            $seedJob,
        ]);

        if (config('tenancy.database_sync')) {
            $chain->onConnection('sync');
        }

        $chain->dispatch();
    }
}
