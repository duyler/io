<?php

declare(strict_types=1);

namespace Duyler\IO\Async\Database;

use Duyler\IO\Future\Future;
use Duyler\IO\Task\SqlQueryTask;
use Fiber;

final class Database
{
    private SqlQueryTask $task;

    public function __construct(string $sql)
    {
        $this->task = new SqlQueryTask($sql);
    }

    public static function query(string $sql): Database
    {
        return new self($sql);
    }

    public function setParams(array $params): Database
    {
        $this->task->setQueryParams($params);
        return $this;
    }

    public function setTypes(array $types): Database
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
