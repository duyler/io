<?php

declare(strict_types=1);

namespace Duyler\IO\File\Task;

use Duyler\IO\TaskInterface;
use Override;
use Yiisoft\Injector\Injector;

/**
 * @psalm-suppress all
 */
final class ReadFileTask implements TaskInterface
{
    private string $path;

    public function setPath(string $path): void
    {
        $this->path = $path;
    }

    #[Override]
    public function run(): mixed
    {
        return file_get_contents($this->path);
    }

    #[Override]
    public function prepare(Injector $injector): void {}
}
