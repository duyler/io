<?php

declare(strict_types=1);

namespace Duyler\IO\Task\File;

use Duyler\IO\TaskInterface;
use Override;

final class WriteFileTask implements TaskInterface
{
    private string $path;
    private string $contents;

    public function setPath(string $path): void
    {
        $this->path = $path;
    }

    public function setContents(string $contents): void
    {
        $this->contents = $contents;
    }

    #[Override]
    public function run(): mixed
    {
        return file_put_contents($this->path, $this->contents);
    }
}
