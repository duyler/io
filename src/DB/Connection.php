<?php

declare(strict_types=1);

namespace Duyler\IO\DB;

use Duyler\IO\DB\Future\FetchAllFuture;
use Duyler\IO\DB\Future\FetchFuture;
use Duyler\IO\DB\Task\SqlQueryTask;
use Duyler\IO\Future;
use Fiber;

class Connection
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
        return new FetchAllFuture(Fiber::suspend($this->task), $class);
    }

    public function fetch(?string $class = null): FetchFuture
    {
        $this->task->setResultMethod('fetch');
        return new FetchFuture(Fiber::suspend($this->task), $class);
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
