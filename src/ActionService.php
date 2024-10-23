<?php

declare(strict_types=1);

namespace Duyler\IO;

use Duyler\DI\ContainerInterface;
use Duyler\DI\ContainerService;
use UnitEnum;

final class ActionService
{
    public function __construct(
        private ContainerInterface $container,
        private string|UnitEnum $actonId,
    ) {}

    public function getActonId(): string|UnitEnum
    {
        return $this->actonId;
    }

    public function getActionContainer(): ContainerService
    {
        return new ContainerService($this->container);
    }
}
