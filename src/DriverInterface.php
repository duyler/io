<?php

declare(strict_types=1);

namespace Duyler\Multiprocess;

use Duyler\Multiprocess\Build\Attribute\Async;

interface DriverInterface
{
    public function process(DriverService $driverService, Async $async): mixed;
}
