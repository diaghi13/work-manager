<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TenantCreateDatabaseCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenant:create-database {database}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create tenant databases';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $database = $this->argument('database');

        if (!$database) {
            $this->error('Database name is required');

            return 1;
        }

        $schemaName = $this->argument('database');

        $this->info("Creating database: {$database}");

        $charset = config("database.connections.mysql.charset",'utf8mb4');

        $collation = config("database.connections.mysql.collation",'utf8mb4_unicode_ci');

        config(["database.connections.mysql.database" => null]);

        $query = "CREATE DATABASE IF NOT EXISTS $schemaName CHARACTER SET $charset COLLATE $collation;";

        DB::statement($query);

        $this->info("Database created: {$database}");
    }
}
