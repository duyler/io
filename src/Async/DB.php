<?php

declare(strict_types=1);

namespace Duyler\IO\Async;

use Duyler\IO\Future\Future;
use Duyler\IO\Task\SqlQueryTask;
use Fiber;

final class DB
{
    private SqlQueryTask $task;

    public function __construct(string $sql)
    {
        $this->task = new SqlQueryTask($sql);
    }

    public static function query(string $sql): DB
    {
        return new self($sql);
    }

    public function setParams(array $params): DB
    {
        $this->task->setQueryParams($params);
        return $this;
    }

    public function setTypes(array $types): DB
    {
        $this->task->setTypes($types);
        return $this;
    }

    public function fetchAll(): Future
    {
        $this->task->setResultMethod('fetchAllAssociative');
        return Fiber::suspend($this->task);
    }

    public function fetchOne(): Future
    {
        $this->task->setResultMethod('fetchOne');
        return Fiber::suspend($this->task);
    }

    public function fetchFirstColumn(): Future
    {
        $this->task->setResultMethod('fetchFirstColumn');
        return Fiber::suspend($this->task);
    }

    public function execute(): Future
    {
        $this->task->setResultMethod('rowCount');
        return Fiber::suspend($this->task);
    }
}
