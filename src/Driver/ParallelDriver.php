<?php

declare(strict_types=1);

namespace Duyler\Multiprocess\Driver;

use Duyler\Multiprocess\Build\Attribute\Async;
use Duyler\Multiprocess\DriverInterface;
use Duyler\Multiprocess\DriverService;
use Duyler\Multiprocess\Exception\ProcessDriverNotAvailableException;
use Fiber;
use parallel\Runtime;

class ParallelDriver implements DriverInterface
{
    public function __construct()
    {
        if (false === extension_loaded('parallel')) {
            throw new ProcessDriverNotAvailableException('parallel');
        }
    }

    public function process(DriverService $driverService, Async $async): mixed
    {
        $runtime = new Runtime();

        $future = $runtime->run($driverService->getValue());

        if ($async->withPromise) {
            return function () use ($future, $runtime) {
                while (false === $future->done()) {
                    Fiber::suspend();
                }

                $future->cancel();
                $runtime->kill();

                return $future->value();
            };
        }

        return $future->value();
    }
}
