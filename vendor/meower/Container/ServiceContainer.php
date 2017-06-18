<?php

namespace Meower\Container;


use Meower\Exceptions\ControllerClassDoesNotExistException;
use Meower\Exceptions\MethodDoesNotExistException;

class ServiceContainer
{
    private $services;
    private $arguments;
    private $action;

    public function __construct($action, $arguments)
    {
        $this->services = require_once APP_PATH . '/config/services.php';
        $this->action = $action;
        $this->arguments = $arguments;
    }

    public function call()
    {
        if ($this->isActionController($this->action)) {
            $this->action = $this->getCallableControllerMethod($this->action);
        }

        $dependencies = $this->getMethodDependencies();

        return call_user_func_array($this->action, $dependencies);
    }

    private function isActionController($action)
    {
        return is_string($action) !== false;
    }

    private function getCallableControllerMethod($action)
    {
        $segments = explode('@', $action);

        $controllerClass = '\App\Http\Controllers\\' . $segments[0];
        $controllerMethod = $segments[1];
        if ($this->isControllerMethodExists($controllerClass, $controllerMethod)) {
            return [$controllerClass, $controllerMethod];
        }
    }

    private function isControllerMethodExists($controllerName, $controllerMethod)
    {
        if (class_exists($controllerName)) {
            if (method_exists($controllerName, $controllerMethod)) {
                return true;
            } else {
                throw new MethodDoesNotExistException("Method {$controllerMethod} doesn't exists!");
            }
        } else {
            throw new ControllerClassDoesNotExistException("Controller class {$controllerName} doesn't exitst!");
        }
    }

    private function getMethodDependencies()
    {
        //TODO: Сделать возможность сделать дефолтным значение аругмента в
        //зависимости от того, где он стоит
        $dependencies = [];
        $parameters = $this->getCallReflector()->getParameters();

        foreach ($parameters as $parameter) {
            if ($parameter->isDefaultValueAvailable()) {
                $dependencies[] = $parameter->getDefaultValue();
            } else {
                $serviceName = $parameter->getClass();
                if ($serviceName) {
                    foreach ($this->services as $serviceNameKey => $dependencyClass) {
                        if ($serviceName->name == $dependencyClass) {
                            $dependencies[] = new $dependencyClass;
                        }
                    }
                }
            }
        }
        return array_merge($dependencies, $this->arguments);
    }

    private function getCallReflector()
    {
        if (is_array($this->action)) {
            return new \ReflectionMethod($this->action[0], $this->action[1]);
        }

        return new \ReflectionFunction($this->action);
    }
}
