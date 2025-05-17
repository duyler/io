<?php

declare(strict_types=1);

namespace Duyler\IO;

use Duyler\IO\File\Task\ReadFileTask;
use Duyler\IO\File\Task\WriteFileTask;
use Fiber;

/**
 * @psalm-suppress all
 */
final class File
{
    public static function read(string $path): Future
    {
        $task = new ReadFileTask();
        $task->setPath($path);
        return Fiber::suspend($task);
    }

    public static function write(string $path, string $contents): Future
    {
        $task = new WriteFileTask();
        $task->setPath($path);
        $task->setContents($contents);
        return Fiber::suspend($task);
    }
}
