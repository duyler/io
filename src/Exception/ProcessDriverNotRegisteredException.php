<?php

declare(strict_types=1);

namespace Duyler\IO\Exception;

use Exception;

class ProcessDriverNotRegisteredException extends Exception
{
    public function __construct(string $driver)
    {
        $message = 'Process driver ' . $driver . ' is not registered in the bus';
        parent::__construct($message);
    }
}
