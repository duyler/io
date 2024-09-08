<?php

declare(strict_types=1);

namespace Duyler\Multiprocess\State;

use Duyler\EventBus\Contract\State\MainSuspendStateHandlerInterface;
use Duyler\EventBus\Formatter\IdFormatter;
use Duyler\EventBus\State\Service\StateMainSuspendService;
use Duyler\EventBus\State\StateContext;
use Duyler\EventBus\State\Suspend;
use Duyler\Multiprocess\Build\AsyncCollection;
use Duyler\Multiprocess\DriverProvider;
use Duyler\Multiprocess\DriverService;

class RunAsyncStateHandler implements MainSuspendStateHandlerInterface
{
    public function __construct(
        private AsyncCollection $asyncCollection,
        private DriverProvider $driverProvider,
    ) {}

    public function handle(StateMainSuspendService $stateService, StateContext $context): void
    {
        $async = $this->asyncCollection->get(IdFormatter::toString($stateService->getActionId()));

        $driver = $this->driverProvider->getDriver($async->driver);

        $resumeValue =  $driver->process(new DriverService($stateService), $async);

        $stateService->setResumeValue($resumeValue);
    }

    public function observed(Suspend $suspend, StateContext $context): bool
    {
        if (false === is_callable($suspend->value)) {
            return false;
        }

        return $this->asyncCollection->has(IdFormatter::toString($suspend->actionId));
    }
}
