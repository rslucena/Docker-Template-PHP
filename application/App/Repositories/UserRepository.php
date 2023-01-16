<?php

namespace Root\Application\Repositories;

use Root\Application\Abstractions\RepositoryAbstraction;

class UserRepository extends RepositoryAbstraction
{

    public function create(array $data): bool|array
    {

        return $this->Mysql->execute(
            /** @lang text */
            "INSERT INTO users (name) VALUES (?)",
            $data
        );

    }

    public function find($id)
    {
    }

    public function update($id, $data)
    {
    }

    public function delete($id)
    {
    }
}