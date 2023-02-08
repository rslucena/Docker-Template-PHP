<?php

namespace App\Abstractions;

use App\Services\{
    MySqlService,
    RedisService
};

abstract class RepositoryAbstraction
{

    protected RedisService $Redis;
    protected MySqlService $Mysql;

    public function __construct()
    {
        $this->Redis = new RedisService();
        $this->Mysql = new MySqlService();
    }

    abstract protected function create(array $Data);

    abstract protected function find(array $Filter);

    abstract protected function update(array $Filter, array $Data);

    abstract protected function delete(array $Filter);

}