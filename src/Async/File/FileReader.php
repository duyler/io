<?php

declare(strict_types=1);

namespace Duyler\IO\Async\File;

use Duyler\IO\Future\Future;
use Duyler\IO\Task\File\ReadFileTask;
use Duyler\IO\TaskInterface;
use Fiber;

final class FileReader
{
    private TaskInterface $task;

    public function __construct()
    {
        $this->task = new ReadFileTask();
    }

    public function setPath(string $path): FileReader
    {
        $this->task->setPath($path);
        return $this;
    }

    public function read(): Future
    {
        return Fiber::suspend($this->task);
    }
}
