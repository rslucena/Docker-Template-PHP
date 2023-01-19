<?php

namespace App\Middlewares;

class AuthenticationMiddleware
{

    /**
     * @return bool
     */
    public function handle():bool
    {
        return !empty($_SESSION['user']);
    }

}