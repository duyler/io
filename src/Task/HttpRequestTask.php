<?php

declare(strict_types=1);

namespace Duyler\IO\Task;

use Duyler\IO\TaskInterface;
use GuzzleHttp\Client;

final class HttpRequestTask implements TaskInterface
{
    private array $options = [];

    public function __construct(
        private string $method,
        private string $url,
    ) {}

    public function setOptions(array $options): HttpRequestTask
    {
        $this->options = $options;
        return $this;
    }

    public function run(): mixed
    {
        $result = [];

        $client = new Client();
        $response = $client->request($this->method, $this->url, $this->options);

        $result['status'] = $response->getStatusCode();
        $result['reason'] = $response->getReasonPhrase();
        $result['body'] = $response->getBody()->getContents();
        $result['headers'] = $response->getHeaders();

        return $result;
    }
}
