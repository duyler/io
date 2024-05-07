<?php

declare(strict_types=1);

namespace Duyler\Multiprocess\Exception;

use Exception;

class ProcessDriverNotAvailableException extends Exception
{
    public function __construct(string $driver)
    {
        $message = 'Async driver ' . $driver . ' is not available in your PHP interpreter';
        parent::__construct($message);
    }
}
