<?php

namespace Core;

class Application
{
    protected $config;

    protected $routes;

    protected $isRouteFounded = false;

    protected $uri;

    public function __construct($config, $routes)
    {
        $this->config = $config;
        $this->routes = $routes;
        $this->uri = rtrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/') . '/';
    }

    public function start()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && array_key_exists('_method', $_POST)) {
            $method = strtoupper($_POST['_method']);
        } else {
            $method = $_SERVER['REQUEST_METHOD'];
        }

        foreach ($this->routes as $route) {
            if (preg_match($route['uri'], $this->uri)) {
                $this->isRouteFounded = true;
                $controllerName = '\App\Controllers\\' . $route['controller'];
                if (class_exists($controllerName)) {
                    $methodAction = $route['action'];
                    if (method_exists($controllerName, $methodAction)) {
                        $args = $this->catchArguments($route['uri']);
                        call_user_func_array([$controllerName, $methodAction], $args);
                    } else {
                        die('Method ' . $methodAction . ' doesn\'t exists!');
                    }
                } else {
                    die('Controller class ' . $controllerName . ' doesn\'t exists!');
                }
            }
        }

        if (!$this->isRouteFounded) {
            die('<b> 404 Page Not Found </b>');
        }
    }

    private function catchArguments($preparedUri)
    {
        $preparedUri = explode('/', $preparedUri);
        $uriWithArgs = explode('/', $this->uri);

        $arguments = [];
        for ($i = 0; $i < count($preparedUri); $i++) {
            if (stripos($preparedUri[$i], '\w+') !== false) {
                $arguments[] = $uriWithArgs[$i];
            }
        }

        return $arguments;
    }
}
