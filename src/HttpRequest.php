<?php

declare(strict_types=1);

namespace Duyler\IO;

use Duyler\IO\Http\Future\SendRequestFuture;
use Duyler\IO\Http\Task\HttpRequestTask;
use Fiber;

/**
 * @psalm-suppress all
 */
final class HttpRequest
{
    private TaskInterface $task;

    public function __construct(
        string $method,
        string $url,
    ) {
        $this->task = new HttpRequestTask($method, $url);
    }

    public static function get(string $url): HttpRequest
    {
        return new self('GET', $url);
    }

    public static function post(string $url): HttpRequest
    {
        return new self('POST', $url);
    }

    public static function put(string $url): HttpRequest
    {
        return new self('PUT', $url);
    }

    public static function delete(string $url): HttpRequest
    {
        return new self('DELETE', $url);
    }

    public static function patch(string $url): HttpRequest
    {
        return new self('PATCH', $url);
    }

    public function setOptions(array $options): HttpRequest
    {
        $this->task->setOptions($options);
        return $this;
    }

    public function send(): SendRequestFuture
    {
        return new SendRequestFuture(Fiber::suspend($this->task));
    }
}
