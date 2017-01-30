<?php

use Core\Application;

define('ROOT', dirname(dirname(__FILE__)));
define('APP_PATH', ROOT . '/app');
define('FRAMEWORK_PATH', ROOT . '/vendor/core');

require ROOT . '/bootstrap/bootstrap.php';

$app = new Application(require(APP_PATH . '/config.php'),
                       $router->getRoutes());
$app->start();
