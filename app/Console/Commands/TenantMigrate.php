<?php

namespace App\Console\Commands;

use App\Models\Tenant;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TenantMigrate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenant:migrate {database} {--seed} {--fresh} {--step} {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate tenant databases';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $database = $this->argument('database');
        $seed = $this->option('seed');
        $fresh = $this->option('fresh');
        $step = $this->option('step');
        $force = $this->option('force');

        $this->info("Migrating database: {$database}");

        if (!$database) {
            $this->error('Database name is required');

            return 1;
        }

        Tenant::switch(false, $database);

        $options = [
            '--database' => 'dynamic',
            '--path' => 'database/migrations/tenant',
            '--force' => $force,
        ];

        if ($step) {
            $options['--step'] = true;
        }

        if ($fresh) {
            $this->call('migrate:fresh', $options);
        }

        $this->call('migrate', $options);

        if ($seed) {
            $this->call('db:seed');
        }

        return 0;
    }
}
