<?php

declare(strict_types=1);

namespace Duyler\Multiprocess\State;

use Duyler\EventBus\Contract\State\MainSuspendStateHandlerInterface;
use Duyler\EventBus\State\Service\StateMainSuspendService;
use Duyler\EventBus\State\StateContext;
use Duyler\EventBus\State\Suspend;
use Duyler\Multiprocess\DriverProvider;
use Duyler\Multiprocess\TaskInterface;

class RunParallelStateHandler implements MainSuspendStateHandlerInterface
{
    public function __construct(
        private DriverProvider $driverProvider,
    ) {}

    public function handle(StateMainSuspendService $stateService, StateContext $context): void
    {
        $driver = $this->driverProvider->getDriver('parallel');

        $resumeValue =  $driver->process($stateService->getValue());

        $stateService->setResumeValue($resumeValue);
    }

    public function observed(Suspend $suspend, StateContext $context): bool
    {
        return $suspend->value instanceof TaskInterface;
    }
}
