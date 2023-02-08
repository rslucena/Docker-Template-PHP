<?php

namespace App\Controllers;

use App\{Repositories\UserRepository, Providers\ValidatorFieldsProviders, Types\AuthType};

use App\Middlewares\{AuthenticationMiddleware, ResponseMiddleware, RequestMiddleware};

class UserController extends UserRepository
{


    /**
     * @return ResponseMiddleware
     * @throws \Exception
     */
    public function Auth():ResponseMiddleware{

        $Response = new ResponseMiddleware();

        $Request = new RequestMiddleware();

        $Errors = (new ValidatorFieldsProviders())
            ->roles([
                'usr' => 'email|max:255',
                'pass' => 'required|string|min:8|max:20'
            ])
            ->values($Request->input())
            ->validate();

        if( !$Errors->isValid() ){
            $Response->Code = 400;
            $Response->Body = json_encode( $Errors->showErrors(), true );
            return $Response;
        }

        $User = $this->find(['email' => $Request->input()['usr']])[0];

        if( empty($User) || password_verify($Request->input()['pass'], $User['password']) ){
            $Response->Code = 400;
            $Response->Body = json_encode("Incorrect credentials", true );
            return $Response;
        }

        $Auth = new AuthenticationMiddleware();
        $Auth->authenticate( $User, AuthType::USERNAME_PASSWORD );
        //continue...
    }

    public function viewProfile()
    {
    }

    public function updateProfile()
    {
    }

    public function deleteUser()
    {
    }

    public function changePassword()
    {
    }

    public function getMetadata()
    {
    }

    public function getAllowedMethods()
    {
    }

}