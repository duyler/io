<?php

declare(strict_types=1);

namespace Duyler\IO;

use Yiisoft\Injector\Injector;

interface TaskInterface
{
    public function run(): mixed;
    public function prepare(Injector $injector): void;
}
