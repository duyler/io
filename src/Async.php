<?php

declare(strict_types=1);

namespace Duyler\Multiprocess;

use Duyler\Multiprocess\Async\HttpRequest;
use Fiber;
use RuntimeException;

final class Async
{
    public static function httpRequest(string $method, string $url): HttpRequest
    {
        Fiber::getCurrent() ?? throw new RuntimeException('Async call available only Fiber context');
        return new HttpRequest($method, $url);
    }
}
