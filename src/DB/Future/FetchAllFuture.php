<?php

declare(strict_types=1);

namespace Duyler\IO\DB\Future;

use Duyler\IO\Future;
use loophp\collection\Collection;
use Yiisoft\Hydrator\Hydrator;

class FetchAllFuture
{
    public function __construct(
        private Future $future,
        private ?string $class = null,
    ) {}

    public function await(): Collection
    {
        /** @var array<array-key, array<string, mixed>> $data */
        $data = $this->future->await();

        if (null === $this->class) {
            return Collection::fromIterable($data);
        }

        $collection = Collection::empty();
        $hydrator = new Hydrator();

        foreach ($data as $item) {
            $collection = $collection->append($hydrator->create($this->class, $item));
        }

        return $collection;
    }
}
