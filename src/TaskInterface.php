<?php

declare(strict_types=1);

namespace Duyler\Multiprocess;

interface TaskInterface
{
    public function run(): mixed;
}
