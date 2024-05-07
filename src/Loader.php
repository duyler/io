<?php

declare(strict_types=1);

namespace Duyler\Multiprocess;

use Duyler\ActionBus\Dto\Context;
use Duyler\DependencyInjection\ContainerInterface;
use Duyler\Framework\Loader\LoaderServiceInterface;
use Duyler\Framework\Loader\PackageLoaderInterface;
use Duyler\Multiprocess\Build\AttributeHandler;
use Duyler\Multiprocess\State\RunAsyncStateHandler;

class Loader implements PackageLoaderInterface
{
    public function __construct(
        private ContainerInterface $container,
    ) {}

    public function load(LoaderServiceInterface $loaderService): void
    {
        $runAsyncStateHandler = $this->container->get(RunAsyncStateHandler::class);
        $attributeHandler = $this->container->get(AttributeHandler::class);

        $loaderService->addStateHandler($runAsyncStateHandler);
        $loaderService->addAttributeHandler($attributeHandler);

        $loaderService->addStateContext(new Context([
            RunAsyncStateHandler::class,
        ]));
    }
}
