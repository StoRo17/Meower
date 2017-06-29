<?php

namespace Meower;

use Meower\Core\Http\Response;
use Meower\Core\Http\Route;
use Meower\DI\DIContainer;
use Meower\Exceptions\ControllerClassDoesNotExistException;
use Meower\Exceptions\MethodDoesNotExistException;

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

    /**
     * Handle the dispatching, call the necessary
     * action with arguments and send response.
     */
    public function run()
    {
        $route = Route::dispatch();
        $action = $route['action'];
        $args = $route['arguments'];

        if (is_callable($action)) {
            $response = call_user_func_array($action, $args);
        } else {
            list($class, $controllerMethod) = explode('@', $action);
            $controller = '\App\Http\Controllers\\' . $class;
            if ($this->isControllerMethodExists($controller, $controllerMethod)) {
                $response = call_user_func_array([new $controller($this->di), $controllerMethod], $args);
            }
        }

        $this->sendResponse($response);
    }

    /**
     * Send response to user.
     * @param Response|string $response
     */
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

    /**
     * Register all services in DI Container.
     */
    private function registerServices()
    {
        foreach ($this->services as $serviceName => $service) {
            $provider = new $service($this->di);
            $provider->init($serviceName);
        }
    }

    /**
     * @param string $controllerName
     * @param string $controllerMethod
     * @return bool
     * @throws ControllerClassDoesNotExistException
     * @throws MethodDoesNotExistException
     */
    private function isControllerMethodExists($controllerName, $controllerMethod)
    {
        if (class_exists($controllerName)) {
            if (method_exists($controllerName, $controllerMethod)) {
                return true;
            } else {
                throw new MethodDoesNotExistException("Method {$controllerMethod} doesn't exist!");
            }
        } else {
            throw new ControllerClassDoesNotExistException("BaseController class {$controllerName} doesn't exist!");
        }
    }

    /**
     * Generates a unique application key
     */
    private function getNewAppKey()
    {
        define('APP_KEY', bin2hex(random_bytes(17)));

        $filePath = APP_PATH . '/config/app.php';

        $appConfig = file_get_contents($filePath);
        $appConfig = preg_replace("#'key'(\\s)*=>(\\s)*false#", "'key'$1=>$2'" . APP_KEY . "'", $appConfig);

        file_put_contents($filePath, $appConfig);
    }
}
