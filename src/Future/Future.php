<?php

declare(strict_types=1);

namespace Duyler\IO\Future;

use Closure;

final class Future
{
    public function __construct(
        private Closure $promise,
    ) {}

    public function await(): mixed
    {
        return ($this->promise)();
    }
}
