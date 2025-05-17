<?php

declare(strict_types=1);

namespace Duyler\IO;

readonly class IOConfig
{
    public function __construct(
        public string $configDir = 'config',
        public string $rootFile = 'docker-compose.yml',
    ) {}
}
