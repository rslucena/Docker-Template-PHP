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
$User->create(['test usr']);
$User->find(1);
$User->update(1, ['usr']);
$User->delete(1);


