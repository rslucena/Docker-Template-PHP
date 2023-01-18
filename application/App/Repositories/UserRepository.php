<?php

namespace Root\Application\Repositories;

use Exception;

use Root\Application\{
    Abstractions\RepositoryAbstraction,
    Providers\ValidatorProviders
};


class UserRepository extends RepositoryAbstraction
{

    private string $tableName = 'user';

    private string $indexColumn = 'id';

    private array $tableColumns = [
        'first_name',
        'last_name',
        'email',
        'password'
    ];

    private array $rolesColumns = [
        'first_name' => 'required|string|min:3|max:100',
        'last_name' => 'required|string|min:3|max:100',
        'email' => 'email|max:255',
        'password' => 'required|string|min:8|max:20',
    ];

    /**
     * @throws \Exception
     */
    protected function create(array $data): bool|array
    {
        try {

            $Errors = ( new ValidatorProviders() )
                ->roles($this->rolesColumns)
                ->values($data)
                ->validate();

            var_dump($Errors->showErrors());

            var_dump($Errors->isValid());

        }catch (Exception $exception ){

            $Errors[] = $exception->getMessage();

        }

        return $Errors;

    }

    protected function find($id)
    {
    }

    protected function update($id, $data)
    {
    }

    protected function delete($id)
    {
    }

}