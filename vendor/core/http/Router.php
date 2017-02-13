<?php

namespace Core\Http;

class Router
{
    protected $action;

    protected $controller;

    protected $routes = [];

    protected $routeParameters = [];

    public function get($uri, $action)
    {
        $this->addRoute('GET', $uri, $action);
    }

    public function post($uri, $action)
    {
        $this->addRoute('POST', $uri, $action);
    }

    public function patch($uri, $action)
    {
        $this->addRoute('PATCH', $uri, $action);
    }

    public function delete($uri, $action)
    {
        $this->addRoute('DELETE', $uri, $action);
    }

    protected function addRoute($method, $uri, $action)
    {
        $updatedUri = $this->convertUri($uri);
        $this->parseAction($action);

        $this->routes[] = [
            'method' => $method,
            'uri' => $updatedUri,
            'controller' => $this->controller,
            'action' => $this->action,
            'parameters' => $this->routeParameters
        ];

        $this->routeParameters = [];
    }

    protected function convertUri($uri)
    {
        $uri = rtrim($uri, '/');

        return '#^' . preg_replace_callback('#{[A-z0-9]+}#i', function () {
            return '\w+';
        }, $uri) . '/{1}$#i';
    }

    protected function parseAction($action)
    {
        $action = explode('@', $action);
        $this->controller = $action[0];
        $this->action = $action[1];
    }

    public function getRoutes()
    {
        return $this->routes;
    }
}

