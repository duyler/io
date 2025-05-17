<?php

declare(strict_types=1);

namespace Duyler\IO\State;

use Duyler\EventBus\Contract\State\MainSuspendStateHandlerInterface;
use Duyler\EventBus\State\Service\StateMainSuspendService;
use Duyler\EventBus\State\StateContext;
use Duyler\EventBus\State\Suspend;
use Duyler\IO\DriverProvider;
use Duyler\IO\TaskInterface;
use Override;
use Yiisoft\Injector\Injector;

class RunTaskStateHandler implements MainSuspendStateHandlerInterface
{
    public function __construct(
        private DriverProvider $driverProvider,
        private Injector $injector,
    ) {}

    #[Override]
    public function handle(StateMainSuspendService $stateService, StateContext $context): void
    {
        $driver = $this->driverProvider->getDriver('parallel');

        /** @var TaskInterface $task */
        $task = $stateService->getValue();
        $task->prepare(
            $this->injector,
        );

        $resumeValue =  $driver->process($task);

        $stateService->setResumeValue($resumeValue);
    }

    #[Override]
    public function observed(Suspend $suspend, StateContext $context): bool
    {
        return $suspend->value instanceof TaskInterface;
    }
}
