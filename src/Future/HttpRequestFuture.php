<?php

declare(strict_types=1);

namespace Duyler\Multiprocess\Future;

use Closure;
use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;

final class HttpRequestFuture
{
    public function __construct(
        private Closure $promise,
    ) {}

    public function await(): ResponseInterface
    {
        $responseArray = ($this->promise)();
        return new Response(
            $responseArray['status'],
            $responseArray['headers'],
            $responseArray['body'] ?? null,
            $responseArray['message'],
        );
    }
}
