<?php

declare(strict_types=1);

namespace Duyler\IO;

readonly class DriverProviderConfig
{
    public function __construct(
        /** @var array<string, string> $drivers */
        public array $drivers = [],
    ) {}
}
