<?php

declare(strict_types=1);

namespace Duyler\Multiprocess\Build\Attribute;

use Duyler\Builder\Build\AttributeHandlerInterface;
use Duyler\Builder\Build\AttributeInterface;
use Duyler\Multiprocess\Build\AttributeHandler;

readonly class Async implements AttributeInterface
{
    public function __construct(
        public bool $withPromise = false,
        public string $driver = 'parallel',
    ) {}

    /** @param AttributeHandler $handler */
    public function accept(AttributeHandlerInterface $handler, mixed $item): void
    {
        $handler->async($this, $item);
    }
}
