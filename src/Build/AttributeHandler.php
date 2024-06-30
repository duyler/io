<?php

declare(strict_types=1);

namespace Duyler\Multiprocess\Build;

use Duyler\ActionBus\Build\Action;
use Duyler\Framework\Build\AttributeHandlerInterface;
use Duyler\Multiprocess\Build\Attribute\Async;

class AttributeHandler implements AttributeHandlerInterface
{
    public function __construct(
        private AsyncCollection $asyncCollection,
    ) {}

    public function getAttributeClasses(): array
    {
        return [Async::class];
    }

    public function async(Async $async, Action $action): void
    {
        $this->asyncCollection->add($action->id, $async);
    }
}
