<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Artisan;

class ProcessTenantDatabaseCreationJob implements ShouldQueue
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
        Artisan::call('tenant:create-database', [
            'database' => $this->database
        ]);
    }
}
