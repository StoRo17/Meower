<?php

namespace Meower;

use Meower\Container\ServiceContainer;
use Meower\Core\Http\Response;
use Meower\Core\Http\Route;
use Meower\DI\DIContainer;

class Application
{
    /**
     * @var array
     */
    private $appConfig;

    /**
     * @var mixed
     */
    private $services;

    /**
     * @var DIContainer
     */
    private $di;

    /**
     * Application constructor.
     * @param DIContainer $di
     */
    public function __construct(DIContainer $di)
    {
        $this->di = $di;

        $this->appConfig = require_once APP_PATH . '/config/app.php';
        $this->services = require_once APP_PATH . '/config/services.php';
        $this->setConfig();
        $this->registerServices();

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
        foreach ($this->appConfig as $key => $value) {
            $defineKey = strtoupper('APP_' . $key);

            if ($defineKey == 'APP_KEY' && $value == false) {
                $this->getNewAppKey();
            } elseif (! defined($defineKey)) {
                define($defineKey, $value);
            }
        }
    }

    private function registerServices()
    {
        foreach ($this->services as $serviceName => $service) {
            $provider = new $service($this->di);
            $provider->init($serviceName);
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
