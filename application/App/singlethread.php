<?php

declare(strict_types=1);

use Root\Application\{
    Providers\DotEnvProvider,
    Services\RedisService,
    Bootstrap\RouterBootstrap,
};

require "../Vendor/autoload.php";

new DotEnvProvider(realpath(__DIR__ . "/../"));

$Redis = new RedisService();
$Routes = new RouterBootstrap();

$Routes->get('/exemple', function (){echo 'Welcome to the homepage';});
$Routes->post('/login', 'AuthController::login');

#Middleware
$Routes->get('/users', '\Root\Application\Controllers\UserController::viewProfile');
$Routes->get('/users/{id}', '\Root\Application\Controllers\UserController::viewProfile', ['\Root\Application\Middlewares\AuthenticationMiddleware']);
$Routes->put('/users/{id}', '\Root\Application\Controllers\UserController::updateProfile', ['\Root\Application\Middlewares\AuthenticationMiddleware']);
$Routes->delete('/users/{id}', '\Root\Application\Controllers\UserController::deleteUser', ['\Root\Application\Middlewares\AuthenticationMiddleware']);
$Routes->options('/users/{id}', '\Root\Application\Controllers\UserController::getAllowedMethods', ['\Root\Application\Middlewares\AuthenticationMiddleware']);

try {
    $Routes->match($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
}catch (Exception $exception){
    die($exception->getMessage());
}
