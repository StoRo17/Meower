<?php

use Meower\Application;
use Meower\DI\DIContainer;

require '../bootstrap/bootstrap.php';
$di = new DIContainer();
$app = new Application($di);

$app->run();

