<?php

declare(strict_types=1);

namespace Duyler\IO\DB;

use Duyler\IO\DB\Future\FetchAllFuture;
use Duyler\IO\DB\Future\FetchFuture;
use Duyler\IO\DB\Task\SqlQueryTask;
use Duyler\IO\Future;
use Fiber;

final class Connection
{
    private SqlQueryTask $task;

    public function __construct(?string $database = null)
    {
        $this->task = new SqlQueryTask($database);
    }

    public function query(string $sql): Connection
    {
        $this->task->setSql($sql);
        return $this;
    }

    public function setParams(array $params): Connection
    {
        $this->task->setQueryParams($params);
        return $this;
    }

    public function fetchAll(?string $class = null): FetchAllFuture
    {
        $this->task->setResultMethod('fetchAll');

        /** @var Future $future */
        $future = Fiber::suspend($this->task);

        return new FetchAllFuture($future, $class);
    }

    public function fetch(?string $class = null): FetchFuture
    {
        $this->task->setResultMethod('fetch');

        /** @var Future $future */
        $future = Fiber::suspend($this->task);

        return new FetchFuture($future, $class);
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
