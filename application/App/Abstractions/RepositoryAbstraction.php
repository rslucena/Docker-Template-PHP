<?php

namespace App\Abstractions;

use App\Services\MySqlService;

abstract class RepositoryAbstraction
{

    protected MySqlService $Mysql;

    public function __construct()
    {
        $this->Mysql = new MySqlService();
    }

    abstract protected function create(array $data);

    abstract protected function find(mixed $id);

    abstract protected function update(mixed $id, array $data);

    abstract protected function delete(mixed $id);

}