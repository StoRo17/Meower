<?php

use Illuminate\Database\Capsule\Manager as Capsule;

// Define first constants
define('ROOT', dirname(dirname(__FILE__)));
define('APP_PATH', ROOT . '/app');
define('FRAMEWORK_PATH', ROOT . '/meower');
define('VIEWS_PATH', APP_PATH . '/views');

// Require some modules
require_once ROOT . '/vendor/autoload.php';
require_once FRAMEWORK_PATH . '/helpers/frameworkHelpers.php';
require_once APP_PATH . '/routes.php';

$dbConfig = require_once APP_PATH . '/config/database.php';

$capsule = new Capsule();
$capsule->addConnection($dbConfig);
$capsule->bootEloquent();
