<?php

declare(strict_types=1);

namespace Duyler\IO\DB\Task;

use Cycle\Database;
use Cycle\Database\Config;
use Cycle\Database\LoggerFactoryInterface;
use Duyler\Builder\ConfigCollector;
use Duyler\Config\ConfigInterface;
use Duyler\Config\FileConfig;
use Duyler\DI\Container;
use Duyler\DI\ContainerConfig;
use Duyler\IO\ActionService;
use Duyler\IO\IOConfig;
use Duyler\IO\TaskInterface;
use Duyler\ORM\DBALConfig;
use Override;

/**
 * @psalm-suppress all
 */
final class SqlQueryTask implements TaskInterface
{
    private array $queryParams = [];
    private string $resultMethod;
    private string $sql;
    private string $configDir;
    private string $rootFile;

    public function __construct(
        private ?string $database,
    ) {}

    public function setSql(string $sql): SqlQueryTask
    {
        $this->sql = $sql;
        return $this;
    }

    public function setQueryParams(array $queryParams): SqlQueryTask
    {
        $this->queryParams = $queryParams;
        return $this;
    }

    public function setResultMethod(string $resultMethod): SqlQueryTask
    {
        $this->resultMethod = $resultMethod;
        return $this;
    }

    #[Override]
    public function run(): mixed
    {
        $containerConfig = new ContainerConfig();
        $configCollector = new ConfigCollector($containerConfig);

        $config = new FileConfig(
            configDir: $this->configDir,
            rootFile: $this->rootFile,
            externalConfigCollector: $configCollector,
        );

        $container = new Container($containerConfig);
        $container->set($config);

        $container->bind(
            [
                ConfigInterface::class => FileConfig::class,
            ],
        );

        /** @var DBALConfig $dbalConfig */
        $dbalConfig = $container->get(DBALConfig::class);

        /** @var LoggerFactoryInterface $logger */
        $logger = null !== $dbalConfig->logger ? $container->get($dbalConfig->logger) : null;

        $dbal = new Database\DatabaseManager(
            config: new Config\DatabaseConfig([
                'default' => $dbalConfig->default,
                'aliases' => $dbalConfig->aliases,
                'databases' => $dbalConfig->databases,
                'connections' => $dbalConfig->connections,
            ]),
            loggerFactory: $logger,
        );

        return $dbal->database($this->database)->query($this->sql, $this->queryParams)->{$this->resultMethod}();
    }

    public function prepare(ActionService $actionService): void
    {
        /** @var IOConfig $ioConfig */
        $ioConfig = $actionService->getActionContainer()->getInstance(IOConfig::class);

        $this->configDir = $ioConfig->configDir;
        $this->rootFile = $ioConfig->rootFile;
    }
}
