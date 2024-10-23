<?php

declare(strict_types=1);

namespace Duyler\IO\Task\HttpRequest;

use Duyler\IO\ActionService;
use Duyler\IO\TaskInterface;
use GuzzleHttp\Client;

final class HttpRequestTask implements TaskInterface
{
    private array $params = [];

    public function __construct(
        private string $method,
        private string $url,
    ) {}

    public function setParams(array $params): HttpRequestTask
    {
        $this->params = $params;
        return $this;
    }

    public function run(): mixed
    {
        $result = [];

        $client = new Client();
        $response = $client->request($this->method, $this->url, $this->params);

        $result['status'] = $response->getStatusCode();
        $result['message'] = $response->getReasonPhrase();
        $result['body'] = $response->getBody()->getContents();
        $result['headers'] = $response->getHeaders();

        return $result;
    }

    public function prepare(ActionService $actionService): void {}
}
