<?php

declare(strict_types=1);

namespace Duyler\IO\State;

use Duyler\EventBus\Contract\State\MainSuspendStateHandlerInterface;
use Duyler\EventBus\State\Service\StateMainSuspendService;
use Duyler\EventBus\State\StateContext;
use Duyler\EventBus\State\Suspend;
use Duyler\IO\ActionService;
use Duyler\IO\DriverProvider;
use Duyler\IO\Task\SqlQueryTask;

class RunSqlQueryTaskStateHandler implements MainSuspendStateHandlerInterface
{
    public function __construct(
        private DriverProvider $driverProvider,
    ) {}

    public function handle(StateMainSuspendService $stateService, StateContext $context): void
    {
        $driver = $this->driverProvider->getDriver('parallel');

        /** @var SqlQueryTask$task */
        $task = $stateService->getValue();
        $task->prepare(
            new ActionService(
                $stateService->getActionContainer(),
                $stateService->getActionId(),
            ),
        );

        $resumeValue =  $driver->process($stateService->getValue());

        $stateService->setResumeValue($resumeValue);
    }

    public function observed(Suspend $suspend, StateContext $context): bool
    {
        return $suspend->value instanceof SqlQueryTask;
    }
}
