<?php
declare(strict_types=1);

use Root\Application\{
    Controller\UserController,
    Provider\DotEnvProvider,
    Repository\UserRepository,
    Service\RedisService};

require "../Vendor/autoload.php";

new DotEnvProvider(realpath(__DIR__ . "/../"));

#---

$Redis = new RedisService();

var_dump($Redis->set('demo', 'demo'));
var_dump($Redis->get('demo'));
var_dump($Redis->del('demo'));
var_dump($Redis->exists('demo'));

#---

$UserRepository = new UserRepository();
$UserController = new UserController();


die();
