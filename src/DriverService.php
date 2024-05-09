<?php

declare(strict_types=1);

namespace Duyler\Multiprocess;

use Duyler\ActionBus\State\Service\StateMainSuspendService;
use Duyler\DependencyInjection\ContainerInterface;

class DriverService
{
    public function __construct(
        private StateMainSuspendService $stateService,
    ) {}

    public function getContainer(): ContainerInterface
    {
        return $this->stateService->getActionContainer();
    }

    public function getValue(): mixed
    {
        return $this->stateService->getValue();
    }
}
