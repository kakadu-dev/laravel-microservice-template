<?php

namespace App\Helpers;

use Exception;
use Kakadu\Microservices\Microservice;
use Symfony\Component\Console\Logger\ConsoleLogger;

/**
 * Class MicroserviceHelper
 * @package App\Helpers
 */
class MicroserviceHelper
{
    /**
     * @param ConsoleLogger $logger
     *
     * @throws Exception
     */
    public static function init(ConsoleLogger $logger): void
    {
        $project = Project::getInstance();

        Microservice::create("{$project->getProjectAlias()}:{$project->getServiceName()}", [
            'ijson' => $project->getIjsonHost(),
            'env'   => $project->getAppEnv(),
        ], $logger);;
    }
}
