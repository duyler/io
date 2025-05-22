<?php

declare(strict_types=1);

namespace Duyler\IO\DB;

use DateTimeImmutable;
use DateTimeInterface;
use Override;
use ReflectionNamedType;
use Yiisoft\Hydrator\Result;
use Yiisoft\Hydrator\TypeCaster\TypeCastContext;
use Yiisoft\Hydrator\TypeCaster\TypeCasterInterface;

/** @psalm-suppress  */
class DateTimeImmutableTypeCast implements TypeCasterInterface
{
    #[Override]
    public function cast(mixed $value, TypeCastContext $context): Result
    {
        $type = $context->getReflectionType();

        if (
            $type instanceof ReflectionNamedType
            && DateTimeInterface::class === $context->getReflection()->getType()->getName()) {
            return Result::success(new DateTimeImmutable($value));
        }

        return Result::fail();
    }
}
