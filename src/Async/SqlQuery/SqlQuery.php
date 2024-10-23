<?php

declare(strict_types=1);

namespace Duyler\IO\Async\SqlQuery;

use Duyler\IO\Task\SqlQuery\SqlQueryTask;

final class SqlQuery
{
    private SqlQueryTask $task;

    public function __construct()
    {
        $this->task = new SqlQueryTask();
    }

    public function query(string $sql): QueryBuilder
    {
        $this->task->setQuery($sql);

        return new QueryBuilder($this->task);
    }
}
