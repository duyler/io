<?php

declare(strict_types=1);

namespace Duyler\IO\Task\SqlQuery;

use Doctrine\DBAL\Connection;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManagerInterface;
use Duyler\Database\DatabaseConfig;
use Duyler\Database\DatabaseConfigInterface;
use Duyler\Database\Provider\ConfigurationProvider;
use Duyler\Database\Provider\ConnectionProvider;
use Duyler\Database\Provider\EntityManagerProvider;
use Duyler\DI\Container;
use Duyler\DI\Definition;
use Duyler\IO\ActionService;
use Duyler\IO\TaskInterface;
use Override;

final class SqlQueryTask implements TaskInterface
{
    private string $query;
    private array $queryParams = [];
    private array $types = [];
    private string $resultMethod;
    private array $connectionConfig;

    public function setQuery(string $query): SqlQueryTask
    {
        $this->query = $query;
        return $this;
    }

    public function setQueryParams(array $queryParams): SqlQueryTask
    {
        $this->queryParams = $queryParams;
        return $this;
    }

    public function setTypes(array $types): SqlQueryTask
    {
        $this->types = $types;
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
        $container->addProviders([
            Configuration::class => ConfigurationProvider::class,
            Connection::class => ConnectionProvider::class,
            EntityManagerInterface::class => EntityManagerProvider::class,
        ]);

        $container->addDefinition(
            new Definition(
                DatabaseConfig::class,
                $this->connectionConfig,
            ),
        );

        /** @var Connection $connection */
        $connection = $container->get(Connection::class);
        $result = $connection->executeQuery($this->query, $this->queryParams, $this->types);

        return $result->{$this->resultMethod}();
    }

    #[Override]
    public function prepare(ActionService $actionService): void
    {
        /** @var DatabaseConfigInterface $connectionConfig */
        $connectionConfig = $actionService->getActionContainer()->getInstance(DatabaseConfigInterface::class);

        $this->connectionConfig = [];
        $this->connectionConfig['entityPaths'] = $connectionConfig->getEntityPaths();
        $this->connectionConfig['isDevMode'] = $connectionConfig->isDevMode();
        $this->connectionConfig['driver'] = $connectionConfig->getDriver();
        $this->connectionConfig['host'] = $connectionConfig->getHost();
        $this->connectionConfig['port'] = $connectionConfig->getPort();
        $this->connectionConfig['database'] = $connectionConfig->getDatabase();
        $this->connectionConfig['username'] = $connectionConfig->getUsername();
        $this->connectionConfig['password'] = $connectionConfig->getPassword();
        $this->connectionConfig['charset'] = $connectionConfig->getCharset();
        $this->connectionConfig['migrationsPaths'] = $connectionConfig->getMigrationsPaths();
        $this->connectionConfig['fixturesPaths'] = $connectionConfig->getFixturesPaths();
    }
}
