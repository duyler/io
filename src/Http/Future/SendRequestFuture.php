<?php

declare(strict_types=1);

namespace Duyler\IO\Http\Future;

use Duyler\IO\Future;
use Duyler\IO\FutureInterface;
use GuzzleHttp\Psr7\Response;
use Override;
use Psr\Http\Message\ResponseInterface;

final class SendRequestFuture implements FutureInterface
{
    public function __construct(
        private Future $future,
    ) {}

    #[Override]
    public function await(): ResponseInterface
    {
        /** @var array<string, int|string|array> $responseArray */
        $responseArray = $this->future->await();
        return new Response(
            status: $responseArray['status'],
            headers: $responseArray['headers'],
            body: $responseArray['body'],
            version: $responseArray['version'],
            reason: $responseArray['reason'],
        );
    }
}
