<?php

declare(strict_types=1);

namespace Duyler\Multiprocess\Build;

use Duyler\Multiprocess\Build\Attribute\Async;

class AsyncCollection
{
    private array $data = [];

    public function add(string $actionId, Async $async): self
    {
        $this->data[$actionId] = $async;
        return $this;
    }

    public function get(string $actionId): Async
    {
        return $this->data[$actionId];
    }

    public function has(string $actionId): bool
    {
        return isset($this->data[$actionId]);
    }
}
