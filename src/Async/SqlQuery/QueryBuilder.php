<?php

declare(strict_types=1);

namespace Duyler\IO\Async\SqlQuery;

use Duyler\IO\Future\SqlQueryFuture;
use Duyler\IO\Task\SqlQuery\SqlQueryTask;
use Fiber;

final class QueryBuilder
{
    public function __construct(
        private SqlQueryTask $task,
    ) {}

    public function setParams(array $params): QueryBuilder
    {
        $this->task->setQueryParams($params);
        return $this;
    }

    public function setTypes(array $types): QueryBuilder
    {
        $this->task->setTypes($types);
        return $this;
    }

    public function fetchAllAssociative(): SqlQueryFuture
    {
        $this->task->setResultMethod('fetchAllAssociative');
        return new SqlQueryFuture(Fiber::suspend($this->task));
    }

    public function fetchOne(): SqlQueryFuture
    {
        $this->task->setResultMethod('fetchOne');
        return new SqlQueryFuture(Fiber::suspend($this->task));
    }

    public function fetchFirstColumn(): SqlQueryFuture
    {
        $this->task->setResultMethod('fetchFirstColumn');
        return new SqlQueryFuture(Fiber::suspend($this->task));
    }

    public function execute(): SqlQueryFuture
    {
        $this->task->setResultMethod('rowCount');
        return new SqlQueryFuture(Fiber::suspend($this->task));
    }
}
