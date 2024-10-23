<?php

declare(strict_types=1);

namespace Duyler\IO\Driver;

use Amp\Serialization\NativeSerializer;
use Closure;
use Duyler\IO\DriverInterface;
use Duyler\IO\Exception\ProcessDriverNotAvailableException;
use Duyler\IO\TaskInterface;
use Fiber;
use parallel\Runtime;
use RuntimeException;
use Throwable;

/**
 * @note This implementation is a simple example
 */
class ParallelDriver implements DriverInterface
{
    public function __construct()
    {
        if (false === extension_loaded('parallel')) {
            throw new ProcessDriverNotAvailableException('parallel');
        }
    }

    public function process(TaskInterface $task): Closure
    {
        $serializer  = new NativeSerializer([$task::class]);

        $serializedTask = $serializer->serialize($task);
        $taskClass = $task::class;

        $callback = function () use ($serializedTask, $taskClass) {
            $dir = dirname('__DIR__') . '/';

            while (!is_file($dir . '/vendor/autoload.php')) {
                if (is_dir(realpath($dir))) {
                    $dir = $dir . '../';
                }

                if (false === realpath($dir)) {
                    throw new RuntimeException('Cannot auto-detect autoload.php');
                }
            }

            require_once $dir . '/vendor/autoload.php';

            try {
                $serializer  = new NativeSerializer([$taskClass]);

                /** @var TaskInterface $task */
                $task = $serializer->unserialize($serializedTask);

                return $task->run();
            } catch (Throwable $exception) {
                throw new RuntimeException($exception->getMessage(), $exception->getCode(), $exception);
            }
        };

        $runtime = new Runtime();

        $future = $runtime->run($callback);

        return function () use ($future, $runtime) {
            while (false === $future->done()) {
                Fiber::suspend();
            }

            $runtime->kill();

            return $future->value();
        };
    }
}
