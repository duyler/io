<?php

declare(strict_types=1);

namespace Duyler\IO;

use Duyler\EventBus\Build\Context;
use Duyler\DI\ContainerInterface;
use Duyler\Builder\Loader\LoaderServiceInterface;
use Duyler\Builder\Loader\PackageLoaderInterface;
use Duyler\IO\State\RunTaskStateHandler;
use Override;

/**
 * @psalm-suppress all
 */
class Loader implements PackageLoaderInterface
{
    public function __construct(
        private ContainerInterface $container,
    ) {}

    #[Override]
    public function load(LoaderServiceInterface $loaderService): void
    {
        /** @var RunTaskStateHandler $runTaskStateHandler */
        $runTaskStateHandler = $this->container->get(RunTaskStateHandler::class);

        $loaderService->addStateHandler($runTaskStateHandler);

        $loaderService->addStateContext(new Context([
            RunTaskStateHandler::class,
        ]));
    }
}
