<?php

namespace Meower;

use Meower\Core\Http\Middleware;
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
     *
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
     * Runs the application.
     */
    public function run()
    {
        $route = Route::dispatch($this->di->request);

        $middleware = new Middleware();
        $neededMiddlewares = $middleware->findNeededMiddleware($route['middleware']);

        $response = $this->handleResponse($neededMiddlewares, $route['action'], $route['arguments']);
        $this->sendResponse($response);
    }

    /**
     * Groups and places middlewares and core action,
     * calls them and return the response.
     *
     * @param array $middlewares
     * @param string $action
     * @param string $args
     * @return Response
     */
    private function handleResponse($middlewares, $action, $args)
    {
        $completeResponse = array_reduce($middlewares, function($nextMiddleware, $middleware) {
            return $this->createLayer($nextMiddleware, $middleware);
        }, $this->createCoreAction($action, $args));

        return $completeResponse($this->di->request, $this->di->response);
    }

    /**
     * Create callback of core action.
     *
     * @param string $action
     * @param string $args
     * @return \Closure
     */
    private function createCoreAction($action, $args)
    {
        return function() use($action, $args) {
            return $this->callAction($action, $args);
        };
    }

    /**
     * Create a layer of middleware, like an onion.
     *
     * @param mixed $nextMiddleware
     * @param mixed $middleware
     * @return \Closure
     */
    private function createLayer($nextMiddleware, $middleware)
    {
        return function($request, $response) use($nextMiddleware, $middleware) {
            return $middleware->handle($request, $response, $nextMiddleware);
        };
    }

    /**
     * Call the necessary controller action and returns the response.
     *
     * @param $action
     * @param $args
     * @return Response
     */
    private function callAction($action, $args)
    {
        list($class, $controllerMethod) = explode('@', $action);
        $controller = '\App\Http\Controllers\\' . $class;
        if ($this->isControllerMethodExists($controller, $controllerMethod)) {
            return call_user_func_array([new $controller($this->di), $controllerMethod], $args);
        }
    }

    /**
     * Send response to user.
     *
     * @param Response $response
     */
    private function sendResponse($response)
    {
        http_response_code($response->getStatusCode());
        foreach ($response->getHeaderLines() as $header) {
            header($header);
        }
        echo $response->getBody();
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
     * Check is controller class or controller method exists.
     *
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
