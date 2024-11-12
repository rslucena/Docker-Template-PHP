<?php

namespace App\Controllers;

use App\{Providers\ValidatorFieldsProviders, Repositories\UserRepository, Types\AuthType};
use App\Middlewares\{AuthenticationMiddleware, RequestMiddleware, ResponseMiddleware};
use Exception;

class UserController extends UserRepository
{

    /**
     * @return ResponseMiddleware
     * @throws Exception
     */
    public function Auth(): ResponseMiddleware
    {

        $Response = new ResponseMiddleware();

        $Request = new RequestMiddleware();

        $Errors = (new ValidatorFieldsProviders())
            ->roles([
                'usr' => 'email|max:255',
                'pass' => 'required|string|min:8|max:20'
            ])
            ->values($Request->input())
            ->validate();

        if (!$Errors->isValid()) {
            $Response->Code = 400;
            $Response->Body = json_encode($Errors->showErrors(), true);
            return $Response;
        }

//        $User = $this->find(['email' => $Request->input()['usr']])[0];
//
//        if (empty($User) || password_verify($Request->input()['pass'], $User['password'])) {
//            $Response->Code = 400;
//            $Response->Body = json_encode("Incorrect credentials", true);
//            return $Response;
//        }

        $Auth = new AuthenticationMiddleware();

        $Token = $Auth->authenticate($Request->input()['usr'], AuthType::USERNAME_PASSWORD);

        $Response->Code = 201;
        $Response->Body = '';
        $Response->Headers = ['Authorization' => 'Bearer ' . $Token];

        return $Response;
    }

    public function ByID(): ResponseMiddleware
    {

        $Response = new ResponseMiddleware();

        $Response->Body = json_encode([
            'id' => 1,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'QYf9z@example.com',
        ]);

        return $Response;
    }

    public function ByParams(): ResponseMiddleware
    {

        $Response = new ResponseMiddleware();

        $Response->Body = json_encode([
            'id' => 1,
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'QYf9z@example.com',
        ]);

        return $Response;
    }

    public function UpdateByID()
    {
    }

    public function DeleteByID()
    {
    }

    public function changePassword()
    {
    }

    public function getAllowedMethods()
    {
        return ['GET', 'PUT', 'DELETE'];
    }

}