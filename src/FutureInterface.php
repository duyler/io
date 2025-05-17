<?php

declare(strict_types=1);

namespace Duyler\IO;

interface FutureInterface
{
    public function await(): mixed;
}
