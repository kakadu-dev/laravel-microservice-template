<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * Class DbCommand
 * @package App\Console\Commands
 */
class DbCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'microservice:db';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create database if not exists';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Create DB by service_name see App/config/app.php
     */
    public function handle()
    {
        $this->info('');

        $schemaName = config("app.service_name");
        $charset    = config("database.connections.mysql.charset", 'utf8mb4');
        $collation  = config("database.connections.mysql.collation", 'utf8mb4_unicode_ci');

        config(["database.connections.mysql.database" => null]);

        $query = "CREATE DATABASE IF NOT EXISTS $schemaName CHARACTER SET $charset COLLATE $collation;";

        DB::statement($query);

        config(["database.connections.mysql.database" => $schemaName]);

        $this->info('created db!');
        $this->info('');
    }
}
