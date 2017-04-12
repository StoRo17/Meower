<?php

define('ROOT', dirname(dirname(__FILE__)));
define('APP_PATH', ROOT . '/app');
define('FRAMEWORK_PATH', ROOT . '/vendor/meower');
define('VIEWS_PATH', APP_PATH . '/views');

require_once ROOT . '/vendor/autoload.php';
require_once FRAMEWORK_PATH . '/helpers/helpers.php';
require_once FRAMEWORK_PATH . '/helpers/framewokHelpers.php';
require_once APP_PATH . '/routes.php';

