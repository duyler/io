<?php

declare(strict_types=1);

namespace Duyler\Multiprocess\State;

use Duyler\ActionBus\Contract\State\MainSuspendStateHandlerInterface;
use Duyler\ActionBus\Formatter\ActionIdFormatter;
use Duyler\ActionBus\State\Service\StateMainSuspendService;
use Duyler\ActionBus\State\StateContext;
use Duyler\ActionBus\State\Suspend;
use Duyler\Multiprocess\Build\AsyncCollection;
use Duyler\Multiprocess\DriverProvider;
use Duyler\Multiprocess\DriverService;

class RunAsyncStateHandler implements MainSuspendStateHandlerInterface
{
    public function __construct(
        private AsyncCollection $asyncCollection,
        private DriverProvider $driverProvider,
    ) {}

    public function handle(StateMainSuspendService $stateService, StateContext $context): mixed
    {
        $async = $this->asyncCollection->get(ActionIdFormatter::toString($stateService->getActionId()));

        $driver = $this->driverProvider->getDriver($async->driver);

        $context->write(ActionIdFormatter::toString($stateService->getActionId()), $async);

        return $driver->process(new DriverService($stateService), $async);
    }

    public function isResumable(Suspend $suspend, StateContext $context): bool
    {
        $async = $context->read(ActionIdFormatter::toString($suspend->actionId));

        if (null !== $async) {
            return false;
        }

        if (false === is_callable($suspend->value)) {
            return false;
        }

        return $this->asyncCollection->has(ActionIdFormatter::toString($suspend->actionId));
    }
}
