<?php

declare(strict_types=1);

namespace Duyler\IO;

use Duyler\IO\DB\Connection;

final class DB
{
    public static function connection(?string $database = null): Connection
    {
        return new Connection($database);
    }
}
