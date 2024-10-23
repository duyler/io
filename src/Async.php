<?php

declare(strict_types=1);

namespace Duyler\IO;

use Duyler\IO\Async\HttpRequest\HttpRequest;
use Duyler\IO\Async\SqlQuery\SqlQuery;
use Fiber;
use RuntimeException;

final class Async
{
    public static function httpRequest(string $method, string $url): HttpRequest
    {
        self::checkContext();
        return new HttpRequest($method, $url);
    }

    public static function sqlQuery(): SqlQuery
    {
        self::checkContext();
        return new SqlQuery();
    }

    private static function checkContext()
    {
        Fiber::getCurrent() ?? throw new RuntimeException('Async call available only Fiber context');
    }
}
