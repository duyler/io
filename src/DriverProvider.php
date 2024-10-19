<?php

declare(strict_types=1);

namespace Duyler\IO;

use Duyler\DI\ContainerInterface;
use Duyler\IO\Driver\ParallelDriver;
use Duyler\IO\Exception\ProcessDriverNotRegisteredException;

class DriverProvider
{
    private const array DRIVERS = [
        'parallel' => ParallelDriver::class,
    ];

    /** @var array<string, DriverInterface> */
    private array $drivers = [];

    public function __construct(
        DriverProviderConfig $driverConfig,
        private ContainerInterface $container,
    ) {
        $drivers = $driverConfig->drivers + self::DRIVERS;

        foreach ($drivers as $id => $driverClass) {
            $this->drivers[$id] = $this->container->get($driverClass);
        }
    }

    public function getDriver(string $driverId): DriverInterface
    {
        if (false === isset($this->drivers[$driverId])) {
            throw new ProcessDriverNotRegisteredException($driverId);
        }

        return $this->drivers[$driverId];
    }
}
