<?php

declare(strict_types=1);

namespace Duyler\IO;

use Duyler\IO\Future\Future;
use Fiber;
use RuntimeException;

final class Async
{
    public static function run(TaskInterface $task): Future
    {
        Fiber::getCurrent() ?? throw new RuntimeException('Async call available only Fiber context');
        return Fiber::suspend($task);
    }
}
