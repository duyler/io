<?php

declare(strict_types=1);

namespace Duyler\Multiprocess\Async;

use Duyler\Multiprocess\Future\HttpRequestFuture;
use Duyler\Multiprocess\Task\HttpRequestTask;
use Duyler\Multiprocess\TaskInterface;
use Fiber;

final class HttpRequest
{
    private TaskInterface $task;

    public function __construct(
        string $method,
        string $url,
    ) {
        $this->task = new HttpRequestTask($method, $url);
    }

    public function setParams(array $params): HttpRequest
    {
        $this->task->setParams($params);
        return $this;
    }

    public function send(): HttpRequestFuture
    {
        $future = Fiber::suspend($this->task);

        return new HttpRequestFuture($future);
    }
}
