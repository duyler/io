<?php

declare(strict_types=1);

namespace Duyler\IO;

interface TaskInterface
{
    public function prepare(ActionService $actionService): void;

    public function run(): mixed;
}
