<?php

declare(strict_types=1);

namespace Duyler\IO;

interface TaskInterface
{
    public function run(): mixed;
}
