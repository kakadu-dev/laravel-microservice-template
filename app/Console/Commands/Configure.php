<?php

namespace App\Console\Commands;

use Illuminate\Support\Arr;
use App\Helpers\{
    ENV,
    Project,
    MicroserviceHelper
};
use App\Helpers\Authorization\Rules;
use App\Helpers\Methods\{
    AuthorizationMethod,
    PanelMethod,
};
use Exception;
use Illuminate\Console\Command;
use Kakadu\Microservices\exceptions\MicroserviceException;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Console\Output\ConsoleOutput;

/**
 * Class Start
 * @package App\Console\Commands
 */
class Configure extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'microservice:configure {manual?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create configuration';

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
     * @throws Exception
     */
    public function handle()
    {
        if ($this->argument('manual') === 'manual') {
            $appEnv = $this->choice(
                'Which environment do you want the application to be initialized in?',
                ['Development', 'Production']
            );

            ENV::putEnv('APP_ENV', $appEnv);
        } else {
            $appEnv = $_SERVER['APP_ENV'] ?? $_ENV['APP_ENV'] ?? 'Development';

            ENV::putEnv('APP_ENV', $appEnv);
        }

        $this->setDebug($appEnv);

        $this->generateKey();

        MicroserviceHelper::init(new ConsoleLogger(new ConsoleOutput()));

        $config = [];
        if (!$this->project->isDisabledControlPanel()) {
            $config = $this->getConfig();
        }

        $this->putConfig($config);

        if ($hasAuthorization = $this->hasAuthorization($config)) {
            $this->importAuthorizationRules();
        } elseif (!$hasAuthorization && !$this->project->isDisabledAuthorization()) {
            $this->importAuthorizationRules();
        }

        $this->info('Configuration project were installed successfully!');
    }

    /**
     * @param string $appEnv
     */
    private function setDebug(string $appEnv): void
    {
        if ($appEnv === 'Production') {
            ENV::putEnv('APP_DEBUG', 'false');
            return;
        }

        ENV::putEnv('APP_DEBUG', 'true');
    }

    /**
     * Set the application key
     */
    private function generateKey(): void
    {
        if (!empty(config('app.key'))) {
            return;
        }

        $this->call('key:generate');
    }

    /**
     * @return mixed
     * @throws MicroserviceException
     */
    public function getConfig()
    {
        $project = Project::getInstance();

        $projectAlias = $project->getProjectAlias();
        $serviceName  = $project->getServiceName();

        $result = PanelMethod::viewProject([
            'alias' => $projectAlias,
            'query' => [
                'expands' => [
                    [
                        'name'  => 'MysqlCredentials',
                        'where' => [
                            'service' => [
                                'or' => [$serviceName, '*'],
                            ],
                        ],
                        'order' => ['-service'],
                        'limit' => 1,
                    ],
                    [
                        'name'     => 'RedisCredentials',
                        'required' => false,
                        'where'    => [
                            'service' => [
                                'or' => [$serviceName, '*'],
                            ],
                        ],
                        'order'    => ['-service'],
                        'limit'    => 1,
                    ],
                    [
                        'name'     => 'MailCredentials',
                        'required' => false,
                        'where'    => [
                            'service' => [
                                'or' => [$serviceName, '*'],
                            ],
                        ],
                        'order'    => ['-service'],
                        'limit'    => 1,
                    ],
                    [
                        'name'     => 'AwsCredentials',
                        'required' => false,
                        'where'    => [
                            'service' => [
                                'or' => [$serviceName, '*'],
                            ],
                        ],
                        'order'    => ['-service'],
                        'limit'    => 1,
                    ],
                    [
                        'name'     => 'ServiceConfig',
                        'required' => false,
                        'where'    => [
                            'service' => $serviceName,
                        ],
                    ],
                    [
                        'name'     => 'FirebaseConfig',
                        'required' => false,
                    ],
                    [
                        'name'      => 'Services',
                        'modelName' => 'Service',
                        'where'     => [
                            'alias' => 'authorization',
                        ],
                    ],
                ],
            ],
        ]);

        if (!$result || empty($result->getResult()['model'] ?? null)) {
            throw new Exception("Project '$projectAlias' not found.");
        }

        return $result->getResult()['model'];
    }

    /**
     * @param array $data
     */
    public function putConfig(array $data = []): void
    {
        if ($mysql = $this->getRemoteConfig($data, 'MysqlCredentials.0', 'projectId')) {
            ENV::putEnv('DB_HOST', ($mysql['host'] ?? ''));
            ENV::putEnv('DB_PORT', ($mysql['port'] ?? ''));
            ENV::putEnv('DB_DATABASE', ($mysql['database'] ?? ''));
            ENV::putEnv('DB_USERNAME', ($mysql['user'] ?? ''));
            ENV::putEnv('DB_PASSWORD', ($mysql['password'] ?? ''));
        }

        // put other service config
    }

    /**
     * @param array $data
     *
     * @return bool
     */
    public function hasAuthorization(array $data): bool
    {
        $authorization = $this->getRemoteConfig($data, 'Services.0', 'alias');

        return ($authorization['alias'] ?? null) === 'authorization';
    }

    /**
     * Get project remote config
     *
     * @param array  $project
     * @param string $location   "test.location.in.array"
     * @param string $checkField "field"
     *
     * @return array|null
     */
    private function getRemoteConfig(array $project, string $location, string $checkField): ?array
    {
        $config = Arr::get($project, $location);

        if (!$config) {
            return null;
        }

        $checkValue = Arr::get($project, "$location.$checkField");

        if (!$checkValue) {
            return null;
        }

        return $config;
    }

    /**
     * Import authorization rules
     *
     * @return void
     * @throws MicroserviceException
     */
    private function importAuthorizationRules(): void
    {
        AuthorizationMethod::importRules(
            $this->project->getServiceName(),
            Rules::VERSION,
            Rules::rules()
        );
    }
}
