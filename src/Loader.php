<?php

declare(strict_types=1);

namespace Duyler\IO;

use Duyler\EventBus\Build\Context;
use Duyler\DI\ContainerInterface;
use Duyler\Builder\Loader\LoaderServiceInterface;
use Duyler\Builder\Loader\PackageLoaderInterface;
use Duyler\IO\State\RunParallelStateHandler;

class Loader implements PackageLoaderInterface
{
    public function __construct(
        private ContainerInterface $container,
    ) {}

    public function load(LoaderServiceInterface $loaderService): void
    {
        /** @var RunParallelStateHandler $runAsyncStateHandler */
        $runAsyncStateHandler = $this->container->get(RunParallelStateHandler::class);

        $loaderService->addStateHandler($runAsyncStateHandler);

        $loaderService->addStateContext(new Context([
            RunParallelStateHandler::class,
        ]));
    }
}
