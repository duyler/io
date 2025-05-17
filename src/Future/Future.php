<?php

declare(strict_types=1);

namespace Duyler\IO\Future;

use Closure;
use Duyler\IO\FutureInterface;

final class Future implements FutureInterface
{
    public function __construct(
        private Closure $promise,
    ) {}

    public function await(): mixed
    {
        return ($this->promise)();
    }
}
