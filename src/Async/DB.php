<?php

declare(strict_types=1);

namespace Duyler\IO\Async;

use Duyler\IO\Future\Future;
use Duyler\IO\Task\SqlQueryTask;
use Fiber;

/**
 * @psalm-suppress all
 */
final class DB
{
    private SqlQueryTask $task;

    public function __construct(?string $database = null)
    {
        $this->task = new SqlQueryTask($database);
    }

    public static function database(?string $database = null): DB
    {
        return new self($database);
    }

    public function query(string $sql): DB
    {
        $this->task->setSql($sql);
        return $this;
    }

    public function setParams(array $params): DB
    {
        $this->task->setQueryParams($params);
        return $this;
    }

    public function fetchAll(): Future
    {
        $this->task->setResultMethod('fetchAll');
        return Fiber::suspend($this->task);
    }

    public function fetch(): Future
    {
        $this->task->setResultMethod('fetch');
        return Fiber::suspend($this->task);
    }

    public function fetchColumn(): Future
    {
        $this->task->setResultMethod('fetchColumn');
        return Fiber::suspend($this->task);
    }

    public function rowCount(): Future
    {
        $this->task->setResultMethod('rowCount');
        return Fiber::suspend($this->task);
    }

    public function columnCount(): Future
    {
        $this->task->setResultMethod('columnCount');
        return Fiber::suspend($this->task);
    }
}
