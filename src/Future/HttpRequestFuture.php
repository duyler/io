<?php

declare(strict_types=1);

namespace Duyler\IO\Future;

use Duyler\IO\FutureInterface;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;

final class HttpRequestFuture implements FutureInterface
{
    public function __construct(
        private Future $future,
    ) {}

    public function await(): ResponseInterface
    {
        $responseArray = $this->future->await();
        return new Response(
            $responseArray['status'],
            $responseArray['headers'],
            $responseArray['body'] ?? null,
            $responseArray['reason'],
        );
    }
}
