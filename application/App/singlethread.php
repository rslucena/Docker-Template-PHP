<?php

declare(strict_types=1);

use App\Bootstrap\{
    RouterBootstrap,
    SettingsBootstrap,
    DotEnvBootstrap
};


require "../Vendor/autoload.php";

DotEnvBootstrap::load(__DIR__);
DotEnvBootstrap::definePath(__DIR__);

SettingsBootstrap::load();
SettingsBootstrap::overwriteIni();

$Routes = new RouterBootstrap();

$Routes->get('/exemple', function () {
    echo 'Welcome to the homepage';
});
$Routes->post('/login', 'AuthController::login');

#Middleware
$Routes->get('/users', 'App\Controllers\UserController::viewProfile');
$Routes->get('/users/{id}', 'App\Controllers\UserController::viewProfile', ['App\Middlewares\AuthenticationMiddleware']);
$Routes->put('/users/{id}', 'App\Controllers\UserController::updateProfile', ['App\Middlewares\AuthenticationMiddleware']);
$Routes->delete('/users/{id}', 'App\Controllers\UserController::deleteUser', ['App\Middlewares\AuthenticationMiddleware']);
$Routes->options('/users/{id}', 'App\Controllers\UserController::getAllowedMethods', ['App\Middlewares\AuthenticationMiddleware']);

try {
    $Routes->match($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
}catch (Exception $exception){
    die($exception->getMessage());
}
