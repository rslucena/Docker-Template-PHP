<?php

declare(strict_types=1);

use App\Bootstrap\{DotEnvBootstrap, RouterBootstrap, SettingsBootstrap};

require "../Vendor/autoload.php";

DotEnvBootstrap::load(dirname(__DIR__, 2));
DotEnvBootstrap::definePath(__DIR__);

SettingsBootstrap::load();
SettingsBootstrap::overwriteIni();

$Routes = new RouterBootstrap();

$Routes->get('/exemple', function () {
    echo 'Welcome to the homepage';
});

$Routes->post('/login', 'App\Controllers\UserController::Auth');

#Middleware
$Routes->get('/users/all', 'App\Controllers\UserController::ByParams', ['App\Middlewares\AuthenticationMiddleware']);
$Routes->get('/users/{id}', 'App\Controllers\UserController::ByID', ['App\Middlewares\AuthenticationMiddleware']);
$Routes->put('/users/{id}', 'App\Controllers\UserController::UpdateByID', ['App\Middlewares\AuthenticationMiddleware']);
$Routes->delete('/users/{id}', 'App\Controllers\UserController::DeleteByID', ['App\Middlewares\AuthenticationMiddleware']);
$Routes->options('/users/{id}', 'App\Controllers\UserController::getAllowedMethods', ['App\Middlewares\AuthenticationMiddleware']);

try {
    $Response = $Routes->match($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
    $Response->end();
} catch (Exception $exception) {
    die($exception->getMessage());
}
