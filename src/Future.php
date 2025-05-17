<?php

declare(strict_types=1);

namespace Duyler\IO;

use Closure;
use Override;

final class Future implements FutureInterface
{
    public function __construct(
        private Closure $promise,
    ) {}

    #[Override]
    public function await(): mixed
    {
        return ($this->promise)();
    }
}
