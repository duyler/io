<?php

declare(strict_types=1);

namespace Duyler\IO\Task\File;

use Duyler\IO\TaskInterface;
use Override;

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
}
