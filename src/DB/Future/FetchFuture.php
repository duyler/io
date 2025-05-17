<?php

declare(strict_types=1);

namespace Duyler\IO\DB\Future;

use Duyler\IO\Future;
use Yiisoft\Hydrator\Hydrator;

class FetchFuture
{
    public function __construct(
        private Future $future,
        private ?string $class = null,
    ) {}

    public function await(): array|object
    {
        $data = $this->future->await();

        if (null === $this->class) {
            return $data;
        }

        $hydrator = new Hydrator();
        return $hydrator->create($this->class, $data);
    }
}
