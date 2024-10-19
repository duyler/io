<?php

declare(strict_types=1);

namespace Duyler\IO;

interface DriverInterface
{
    public function process(TaskInterface $task): mixed;
}
