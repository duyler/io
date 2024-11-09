<?php

declare(strict_types=1);

namespace Duyler\IO\Async\File;

use Duyler\IO\Future\Future;
use Duyler\IO\Task\File\WriteFileTask;
use Duyler\IO\TaskInterface;
use Fiber;

final class FileWriter
{
    private TaskInterface $task;

    public function __construct()
    {
        $this->task = new WriteFileTask();
    }

    public function setPath(string $path): FileWriter
    {
        $this->task->setPath($path);
        return $this;
    }

    public function setContents(string $contents): FileWriter
    {
        $this->task->setContents($contents);
        return $this;
    }

    public function write(): Future
    {
        return Fiber::suspend($this->task);
    }
}
