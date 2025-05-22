<?php

declare(strict_types=1);

namespace Duyler\IO\DB;

use Override;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use ReflectionNamedType;
use Yiisoft\Hydrator\Result;
use Yiisoft\Hydrator\TypeCaster\TypeCastContext;
use Yiisoft\Hydrator\TypeCaster\TypeCasterInterface;

class UuidTypeCaster implements TypeCasterInterface
{
    #[Override]
    public function cast(mixed $value, TypeCastContext $context): Result
    {
        $type = $context->getReflectionType();

        if (
            $type instanceof ReflectionNamedType
            && UuidInterface::class === $context->getReflection()->getType()->getName()) {
            return Result::success(Uuid::fromString($value));
        }

        return Result::fail();
    }
}
