<?php

namespace App\Abstractions;

class QueryBuilderAbstraction
{
    protected $table;
    protected $columns = [];
    protected $joins = [];
    protected $where = [];
    protected $groupBy;
    protected $having;
    protected $havingBindings = [];
    protected $orderBy;
    protected $limit;
    protected $offset;

    public function columns(array $columns = [])
    {
        $this->columns = $columns;
        return $this;
    }

    public function from($table)
    {
        $this->table = $table;
        return $this;
    }

    public function join($table, $on, $type = 'INNER')
    {
        $this->joins[] = "$type JOIN $table ON $on";
        return $this;
    }

    public function leftJoin($table, $on)
    {
        $this->joins[] = "LEFT JOIN $table ON $on";
        return $this;
    }

    public function rightJoin($table, $on)
    {
        $this->joins[] = "RIGHT JOIN $table ON $on";
        return $this;
    }

    public function where($column, $value, $operator = '=')
    {
        $this->where[] = "$column $operator :$column";
        return $this;
    }

    public function groupBy($groupBy)
    {
        $this->groupBy = $groupBy;
        return $this;
    }

    public function having($having, $bindings = [])
    {
        $this->having = $having;
        $this->havingBindings = $bindings;
        return $this;
    }

    public function orderBy($orderBy, $direction = 'ASC')
    {
        $this->orderBy = "$orderBy $direction";
        return $this;
    }

    public function limit($limit)
    {
        $this->limit = $limit;
        return $this;
    }

    public function offset($offset)
    {
        $this->offset = $offset;
        return $this;
    }

    public function build(): string
    {
        $columns = '*';
        if (!empty($this->columns)) {
            $columns = implode(', ', $this->columns);
        }

        $sql = /** @lang text */ "SELECT $columns FROM $this->table";

        if (!empty($this->joins)) {
            $sql .= ' ' . implode(' ', $this->joins);
        }

        if (!empty($this->where)) {
            $sql .= ' WHERE ' . implode(' AND ', $this->where);
        }

        if ($this->groupBy) {
            $sql .= " GROUP BY $this->groupBy";
        }

        if ($this->having) {
            $sql .= " HAVING $this->having";
        }

        if ($this->orderBy) {
            $sql .= " ORDER BY $this->orderBy";
        }

        if ($this->limit) {
            $sql .= " LIMIT $this->limit";
        }

        if ($this->offset) {
            $sql .= " OFFSET $this->offset";
        }

        return $sql;
    }

}