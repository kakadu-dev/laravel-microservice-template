<?php

namespace App\Console\Commands;

use App\Helpers\{
    MicroserviceHelper,
    Project
};
use Exception;
use Illuminate\Console\Command;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Kakadu\Microservices\Microservice;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Console\Output\ConsoleOutput;

/**
 * Class Start
 * @package App\Console\Commands
 */
class Start extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'microservice:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start microservice';

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
     * @throws Exception
     */
    public function handle()
    {
        MicroserviceHelper::init(new ConsoleLogger(new ConsoleOutput()));

        Microservice::getInstance()->start(function ($method, $params) {
            $route = str_replace('.', '/', $method);

            $kernel = $this->getApp()->make(\Illuminate\Contracts\Http\Kernel::class);

            return tap($kernel->handle(
                $request = Request::create($route, 'POST', $params)
            ));
        });
    }

    /**
     * @return Application
     */
    private function getApp(): Application
    {
        $app = new Application(
            Project::getInstance()->getAppDirName()
        );

        $app->singleton(
            \Illuminate\Contracts\Http\Kernel::class,
            \App\Http\Kernel::class
        );

        $app->singleton(
            \Illuminate\Contracts\Console\Kernel::class,
            \App\Console\Kernel::class
        );

        $app->singleton(
            \Illuminate\Contracts\Debug\ExceptionHandler::class,
            \App\Exceptions\Handler::class
        );

        return $app;
    }
}
