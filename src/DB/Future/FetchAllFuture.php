<?php

declare(strict_types=1);

namespace Duyler\IO\DB\Future;

use Duyler\IO\DB\DateTimeImmutableTypeCast;
use Duyler\IO\DB\UuidTypeCaster;
use Duyler\IO\Future;
use Illuminate\Support\Collection;
use Yiisoft\Hydrator\Hydrator;
use Yiisoft\Hydrator\TypeCaster\CompositeTypeCaster;
use Yiisoft\Hydrator\TypeCaster\EnumTypeCaster;
use Yiisoft\Hydrator\TypeCaster\HydratorTypeCaster;
use Yiisoft\Hydrator\TypeCaster\PhpNativeTypeCaster;

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

        $typeCaster = new CompositeTypeCaster(
            new UuidTypeCaster(),
            new PhpNativeTypeCaster(),
            new DateTimeImmutableTypeCast(),
            new HydratorTypeCaster(),
            new EnumTypeCaster(),
        );

        $hydrator = new Hydrator($typeCaster);

        foreach ($data as $item) {
            $collection->add($hydrator->create($this->class, $item));
        }

        return $collection;
    }
}
