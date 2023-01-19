<?php

namespace Root\Application\Middlewares;

class AuthenticationMiddleware
{

    public function handle()
    {
        return !empty($_SESSION['user']);
    }

}