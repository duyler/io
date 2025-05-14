<?php

declare(strict_types=1);

namespace Duyler\IO\Async;

use Duyler\IO\Future\Future;
use Duyler\IO\Task\File\ReadFileTask;
use Duyler\IO\Task\File\WriteFileTask;
use Fiber;

final class File
{
    public static function get(string $path): Future
    {
        $task = new ReadFileTask();
        $task->setPath($path);
        return Fiber::suspend($task);
    }

    public static function put(string $path, string $contents): Future
    {
        $task = new WriteFileTask();
        $task->setPath($path);
        $task->setContents($contents);
        return Fiber::suspend($task);
    }
}
