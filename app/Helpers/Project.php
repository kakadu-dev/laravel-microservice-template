<?php

namespace App\Helpers;

/**
 * Class Project
 * @package App\Helpers
 */
class Project
{
    /**
     * @var string
     */
    private string $appEnv;

    /**
     * @var bool
     */
    private bool $appDebug;

    /**
     * @var string
     */
    private string $projectAlias;

    /**
     * @var string
     */
    private string $panelAlias;

    /**
     * @var string
     */
    private string $serviceName;

    /**
     * @var string|null
     */
    private ?string $ijsonHost;

    /**
     * @var string
     */
    private string $appDirName;

    /**
     * @var bool
     */
    private bool $isDisabledControlPanel;

    /**
     * @var bool
     */
    private bool $isDisabledAuthorization;

    /**
     * @var bool
     */
    private bool $isDisabledSeeder;

    /**
     * @var Project|null
     */
    private static ?Project $instance = null;

    /**
     * Project constructor.
     */
    public function __construct()
    {
        $this->appDirName              = $_ENV['APP_BASE_PATH'] ?? dirname(__DIR__, 2);
        $this->serviceName             = config('app.service_name');
        $this->appDebug                = config('app.debug');
        $this->appEnv                  = config('app.env');
        $this->projectAlias            = config('app.name');
        $this->ijsonHost               = config('app.ijson_host');
        $this->panelAlias              = config('app.panel_alias');
        $this->isDisabledControlPanel  = config('app.control_panel_disable') === 'yes';
        $this->isDisabledAuthorization = config('app.authorization_disable') === 'yes';
        $this->isDisabledSeeder        = config('app.database_seeder_disable') === 'yes';
    }

    /**
     * @return string
     */
    public function getAppEnv(): string
    {
        return $this->appEnv;
    }

    /**
     * @return bool
     */
    public function getAppDebug(): bool
    {
        return $this->appDebug;
    }

    /**
     * @return string
     */
    public function getProjectAlias(): string
    {
        return $this->projectAlias;
    }

    /**
     * @return string
     */
    public function getPanelAlias(): string
    {
        return $this->panelAlias;
    }

    /**
     * @return string
     */
    public function getServiceName(): string
    {
        return $this->serviceName;
    }

    /**
     * @return string|null
     */
    public function getIjsonHost(): ?string
    {
        return $this->ijsonHost;
    }

    /**
     * @return string
     */
    public function getAppDirName(): string
    {
        return $this->appDirName;
    }

    /**
     * @return bool
     */
    public function isDisabledControlPanel(): bool
    {
        return $this->isDisabledControlPanel;
    }

    /**
     * @return bool
     */
    public function isDisabledAuthorization(): bool
    {
        return $this->isDisabledAuthorization;
    }

    /**
     * @return bool
     */
    public function isDisabledSeeder(): bool
    {
        return $this->isDisabledSeeder;
    }

    /**
     * @return Project
     */
    public static function getInstance(): Project
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }
}
