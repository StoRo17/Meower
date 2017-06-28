<?php

require '../bootstrap/bootstrap.php';

$di = new Meower\DI\DIContainer();
$app = new Meower\Application($di);

$app->run();

