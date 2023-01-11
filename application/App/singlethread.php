<?php
declare(strict_types=1);

use Controller\UserController;
use Provider\DotEnvProvider;
use Repository\UserRepository;

require "../Vendor/autoload.php";

$DotEnvProvider = (new DotEnvProvider())->example();

$UserController = (new UserController())->example();

$UserRepository = (new UserRepository())->example();

var_dump($DotEnvProvider, $UserController, $UserRepository);

die();
