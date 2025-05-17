<?php

declare(strict_types=1);

namespace Duyler\IO;

use Duyler\EventBus\Build\Context;
use Duyler\DI\ContainerInterface;
use Duyler\Builder\Loader\LoaderServiceInterface;
use Duyler\Builder\Loader\PackageLoaderInterface;
use Duyler\IO\State\RunDefaultTaskStateHandler;
use Duyler\IO\State\RunSqlQueryTaskStateHandler;

/**
 * @psalm-suppress all
 */
class Loader implements PackageLoaderInterface
{
    public function __construct(
        private ContainerInterface $container,
    ) {}

    public function load(LoaderServiceInterface $loaderService): void
    {
        /** @var RunDefaultTaskStateHandler $runDefaultTaskStateHandler */
        $runDefaultTaskStateHandler = $this->container->get(RunDefaultTaskStateHandler::class);

        /** @var RunSqlQueryTaskStateHandler $runSqlQueryTaskStateHandler */
        $runSqlQueryTaskStateHandler = $this->container->get(RunSqlQueryTaskStateHandler::class);

        $loaderService->addStateHandler($runDefaultTaskStateHandler);
        $loaderService->addStateHandler($runSqlQueryTaskStateHandler);

        $loaderService->addStateContext(new Context([
            RunDefaultTaskStateHandler::class,
        ]));
    }
}
