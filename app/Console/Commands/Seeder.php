<?php

namespace App\Console\Commands;

use App\Helpers\Project;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Command\Command as Response;

class Seeder extends Command
{
    private const LOCAL_FOLDER_PATH = 'database/microservices';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'microservice:seed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seeding data into DB';

    /**
     * @var Project
     */
    public Project $project;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->project = Project::getInstance();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if ($this->project->isDisabledSeeder()) {
            $this->info('seeding disabled!');
            return Response::SUCCESS;
        }

        foreach ($this->getPaths() as $path) {
            $this->info($path);
            DB::statement(file_get_contents($path));
        }

        $this->info('seeding completed!');

        return Response::SUCCESS;
    }

    /**
     * @return array
     */
    private function getAllSqlPath(): array
    {
        $path = $this->project->getAppDirName()
            . DIRECTORY_SEPARATOR
            . self::LOCAL_FOLDER_PATH
            . DIRECTORY_SEPARATOR;

        return glob($path . '*.sql');
    }

    /**
     * there are we sort slq file with relation order
     *
     * @return array
     */
    private function getPaths(): array
    {
        $filesPath = [];
        foreach ($this->getAllSqlPath() as $path) {
            if (strpos($path, 'file.sql') !== false) {
                $filesPath[0] = $path;
            }

        }

        ksort($filesPath);

        return $filesPath;
    }
}
