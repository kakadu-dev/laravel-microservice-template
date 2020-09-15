<?php

namespace App\Helpers\Methods;

use Kakadu\Microservices\exceptions\MicroserviceException;
use Kakadu\Microservices\Microservice;

/**
 * Class PanelMethod
 * @package App\Helpers\Methods
 */
class SettingMethod extends AbstractMethod
{
    const VIEW = 'rpc.settings.view';

    /**
     * @var string
     */
    public static string $serviceName = 'base';

    /**
     * @param $data
     *
     * @return array|string|null
     * @throws MicroserviceException
     */
    public static function getSettingByName($data)
    {
        return Microservice::getInstance()
            ->sendServiceRequest(
                self::getServiceMethod(),
                self::VIEW,
                array_merge($data,
                [
                    'payload' => [
                        'authentication' => [
                            'userId' => 1,
                            'authProvider' => 'jwt',
                            'isAuth' => true
                        ],
                        'authorization' => [
                            'role' => 'admin'
                        ]
                    ]
                ]),
                true,
                [
                    'headers' => [
                        'Option' => 'if present',
                    ],
                ]
            )->getResult();
    }
}
