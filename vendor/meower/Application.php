<?php

namespace Meower;

use Meower\Container\ServiceContainer;
use Meower\Http\Response;
use Meower\Http\Route;

class Application
{
    private $config;

    public function __construct()
    {
        $this->config = require_once APP_PATH . '/config/app.php';
        $this->setConfig();

        ini_set('display_errors', APP_DEBUG);
    }

    public function run()
    {
        $route = Route::dispatch();
        $action = $route['action'];
        $args = $route['arguments'];

        $response = (new ServiceContainer($action, $args))->call();
        $this->sendResponse($response);
    }

    private function sendResponse($response)
    {
        if ($response instanceof Response) {
            http_response_code($response->getStatusCode());
            foreach ($response->getHeaderLines() as $header) {
                header($header);
            }
            echo $response->getBody();
        } elseif (is_string($response)) {
            echo $response;
        }
    }

    private function setConfig()
    {
        foreach ($this->config as $key => $value) {
            $defineKey = strtoupper('APP_' . $key);

            if ($defineKey == 'APP_KEY' && $value == false) {
                $this->getNewAppKey();
            } elseif (! defined($defineKey)) {
                define($defineKey, $value);
            }
        }
    }

    private function getNewAppKey()
    {
        define('APP_KEY', bin2hex(random_bytes(17)));

        $filePath = APP_PATH . '/config/app.php';

        $appConfig = file_get_contents($filePath);
        $appConfig = preg_replace("#'key'(\\s)*=>(\\s)*false#", "'key'$1=>$2'" . APP_KEY . "'", $appConfig);

        file_put_contents($filePath, $appConfig);
    }
}
