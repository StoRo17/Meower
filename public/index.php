<?php

use Meower\Application;

define('ROOT', dirname(dirname(__FILE__)));
define('APP_PATH', ROOT . '/app');
define('FRAMEWORK_PATH', ROOT . '/vendor/meower');
define('VIEWS_PATH', APP_PATH . '/views');

require ROOT . '/bootstrap/bootstrap.php';

$app = new Application();

$app->run();

