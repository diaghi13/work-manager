<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Artisan;

class ProcessTenantMigrationJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected string $database
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Artisan::call('tenant:migrate', [
            'database' => $this->database,
            '--seed' => true,
            '--step' => true,
            '--force' => true,
        ]);
    }
}
