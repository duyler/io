<?php

declare(strict_types=1);

namespace Duyler\Multiprocess;

interface DriverInterface
{
    public function process(TaskInterface $task): mixed;
}
