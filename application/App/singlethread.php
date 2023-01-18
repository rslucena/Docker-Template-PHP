<?php

declare(strict_types=1);

use Root\Application\{
    Providers\DotEnvProvider,
    Services\RedisService,
    Controllers\UserController
};

require "../Vendor/autoload.php";

new DotEnvProvider(realpath(__DIR__ . "/../"));

$Redis = new RedisService();

$Redis->get('test');
$Redis->set('test', []);
$Redis->del('test');
$Redis->exists('test');

$User = new UserController();
$User->createExempleFunction([
    'name' => 'test first name',
    'last_name' => 'test last name',
    'email' => 'test@localhost.test',
    'password' => rand(1000000000,9999999999),
]);
