<?php

namespace App\Repositories;

use App\Abstractions\RepositoryAbstraction;

class UserRepository extends RepositoryAbstraction
{

    private string $TableName = 'user';

    private string $IndexColumn = 'id';

    private array $TableColumns = [
        'first_name',
        'last_name',
        'email',
        'password'
    ];

    private array $RolesColumns = [
        'first_name' => 'required|string|min:3|max:100',
        'last_name' => 'required|string|min:3|max:100',
        'email' => 'email|max:255',
        'password' => 'required|string|min:8|max:20',
    ];

    /**
     * @throws \Exception
     */
    protected function create(array $Data)
    {
    }

    protected function find(array $Filter): array
    {
        $Key = "usr:" . implode(":", $Filter);

        $Query = $this->Mysql->from('usr');
        $Query->columns(['id', 'first_name', 'last_name', 'email', 'password', 'created_at']);
        foreach ($Filter as $Key => $value) {
            $Query->where($Key, $value);
        }

        $User = json_decode($this->Redis->get($Key), true);

        $User = !empty($User) ? $User : $this->Mysql->execute($Query->build(), $Filter);

        $this->Redis->set(json_encode($Key, true), $User, 60);

        return $User;

    }

    protected function update($Filter, $Data)
    {
    }

    protected function delete($Filter)
    {
    }

}