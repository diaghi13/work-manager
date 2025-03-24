<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TenantSeedCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenant:seed {database} {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed tenant databases';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $database = $this->argument('database');
        $force = $this->option('force');

        $this->info("Seeding database: {$database}");

        if (!$database) {
            $this->error('Database name is required');

            return 1;
        }

        config()->set('database.connections.dynamic.database', $database);

        DB::setDefaultConnection('dynamic');
        DB::reconnect('dynamic');

        $options = [
            '--database' => 'dynamic',
            '--force' => $force,
        ];

        $this->call('db:seed', $options);

        $this->info("Database seeded: {$database}");

        return 0;
    }
}
