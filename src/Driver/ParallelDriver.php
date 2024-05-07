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

    public function process(DriverService $stateService, Async $async): mixed
    {
        $runtime = new Runtime();

        if (null === $async->companion) {
            $future = $runtime->run($stateService->getValue());
        } else {
            $companion = $stateService->getContainer()->get($async->companion);
            $future = $runtime->run($companion, [$stateService->getValue()]);
        }

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
