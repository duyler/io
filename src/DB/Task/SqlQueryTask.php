<?php

declare(strict_types=1);

namespace Duyler\IO\DB\Task;

use Cycle\Database;
use Cycle\Database\Config;
use Cycle\Database\LoggerFactoryInterface;
use Duyler\DI\Container;
use Duyler\IO\ActionService;
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
    private array $connectionConfig;
    private string $sql;

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
        $container = new Container();

        /** @var LoggerFactoryInterface $logger */
        $logger = null !== $this->connectionConfig['logger']
            ? $container->get($this->connectionConfig['logger'])
            : null;

        $connections = [];

        foreach ($this->connectionConfig['connections'] as $name => $connection) {

            $connectionConfigClass = $connection['connection']['className'];

            if ($connectionConfigClass === Config\Postgres\TcpConnectionConfig::class) {
                unset($connection['connection']['className']);
                unset($connection['connection']['charset']);
                $connectionConfig = new Config\Postgres\TcpConnectionConfig(...$connection['connection']);
            } else {
                unset($connection['connection']['className']);
                $connectionConfig = new Config\MySQL\TcpConnectionConfig(...$connection['connection']);
            }

            $connections[$name] = new $connection['className'](
                connection: $connectionConfig,
                schema: $connection['schema'],
                driver: $connection['driver'],
                reconnect: $connection['reconnect'],
                timezone: $connection['timezone'],
                queryCache: $connection['queryCache'],
                readonlySchema: $connection['readonlySchema'],
                readonly: $connection['readonly'],
                options: $connection['options'],
            );
        }

        $dbal = new Database\DatabaseManager(
            config: new Config\DatabaseConfig([
                'default' => $this->connectionConfig['default'],
                'aliases' => $this->connectionConfig['aliases'],
                'databases' => $this->connectionConfig['databases'],
                'connections' => $connections,
            ]),
            loggerFactory: $logger,
        );

        return $dbal->database($this->database)->query($this->sql, $this->queryParams)->{$this->resultMethod}();
    }

    public function prepare(ActionService $actionService): void
    {
        /** @var DBALConfig $dbalConfig */
        $dbalConfig = $actionService->getActionContainer()->getInstance(DBALConfig::class);

        $connections = [];

        foreach ($dbalConfig->connections as $name => $driverConfig) {
            $connections[$name] = [
                'className' => get_class($driverConfig),
                'schema' => $driverConfig->schema,
                'driver' => $driverConfig->driver,
                'reconnect' => $driverConfig->reconnect,
                'timezone' => $driverConfig->timezone,
                'queryCache' => $driverConfig->queryCache,
                'readonlySchema' => $driverConfig->readonlySchema,
                'readonly' => $driverConfig->readonly,
                'options' => $driverConfig->options,
                'connection' => [
                    'className' => get_class($driverConfig->connection),
                    'database' => $driverConfig->connection->database,
                    'host' => $driverConfig->connection->host,
                    'port' => $driverConfig->connection->port,
                    'user' => $driverConfig->connection->user,
                    'password' => $driverConfig->connection->password,
                    'options' => $driverConfig->connection->options,
                    'charset' => $driverConfig->connection->charset ?? null,
                ],
            ];
        }

        $this->connectionConfig = [];
        $this->connectionConfig['default'] = $dbalConfig->default;
        $this->connectionConfig['aliases'] = $dbalConfig->aliases;
        $this->connectionConfig['databases'] = $dbalConfig->databases;
        $this->connectionConfig['connections'] = $connections;
        $this->connectionConfig['logger'] = $dbalConfig->logger;
    }
}
