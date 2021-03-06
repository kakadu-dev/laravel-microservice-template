<?php

namespace App\Helpers\Methods;

use App\Helpers\Project;
use Kakadu\Microservices\exceptions\MicroserviceException;
use Kakadu\Microservices\{
    Microservice,
    MjResponse
};

/**
 * Class PanelMethod
 * @package App\Helpers\Methods
 */
class PanelMethod extends AbstractMethod
{
    const PROJECTS_VIEW = 'projects.view';

    /**
     * @var string
     */
    public static string $serviceName = 'control-panel';

    /**
     * Get project
     *
     * @param array $data
     *
     * @return MjResponse|null
     * @throws MicroserviceException
     */
    public static function viewProject(array $data): ?MjResponse
    {
        return Microservice::getInstance()
            ->sendServiceRequest(
                self::getServiceMethod(),
                self::PROJECTS_VIEW,
                $data,
                true,
                [
//                    'headers' => [
//                        'Option' => 'if present',
//                    ],
                ]
            );
    }

    /**
     * @inheritDoc
     */
    protected static function getProjectAlias(): string
    {
        return Project::getInstance()->getPanelAlias();
    }
}
