<?php

namespace Meower\Core\Http;

class Route
{
    private $route;
    private $method;
    public static $home;
    private static $routes;
    private static $middleware = [];
    private static $prefix = '/';

    public function __construct($route, $method)
    {
        $this->route = $route;
        $this->method = $method;
    }

    public static function get($route, $callback)
    {   
        $route = self::generateRoute($route);

        self::add($route, $callback, 'GET');

        return new self($route, 'GET');
    }

    public static function post($route, $callback)
    {
        $route = self::generateRoute($route);

        self::add($route, $callback, 'POST');

        return new self($route, 'POST');
    }

    public static function put($route, $callback)
    {
        $route = self::generateRoute($route);

        self::add($route, $callback, 'PUT');

        return new self($route, 'PUT');
    }

    public static function patch($route, $callback)
    {
        $route = self::generateRoute($route);

        self::add($route, $callback, 'PATCH');

        return new self($route, 'PATCH');
    }

    public static function delete($route, $callback)
    {
        $route = self::generateRoute($route);

        self::add($route, $callback, 'DELETE');

        return new self($route, 'DELETE');
    }

    public static function group($options, $callback)
    {
        if (! empty($options['prefix'])) {
            $prefix = str_replace('.', '/', $options['prefix']);
            $prefix = '/' . trimBothSlashes($prefix) . '/';

            self::$prefix = $prefix;
        }

        if (! empty($options['middleware'])) {
            self::$middleware = is_array($options['middleware']) ? $options['middleware'] : [$options['middleware']];
        }

        $callback();

        self::$prefix = '/';
        self::$middleware = [];
    }

    private static function generateRoute($route)
    {
        $route = str_replace('.', '/', $route);
        $route = trimBothSlashes($route);
        $route = self::$prefix . $route;

        return $route;
    }

    private static function add($route, $callback, $method)
    {
        self::$routes[$method][$route]['action'] = $callback;
        self::$routes[$method][$route]['middleware'] = self::$middleware;
    }

    public static function dispatch()
    {
        require_once(APP_PATH . '/routes.php');

        $method = self::getRequestMethod();

        $routes = self::$routes[$method];
        $urls = array_keys($routes);

        $url = rtrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/') . '/';

        foreach ($urls as $routeUrl) {
            $preparedUrl = self::convertUrl($routeUrl);

            if (preg_match('#^' . $preparedUrl . '/{1}$#i', $url, $match)) {
                unset($match[0]);
                $request = $routes[$routeUrl];
                $request['arguments'] = array_values($match);
            }
        }

        if (!isset($request)) {
            $request['action'] = function () {
                echo '404 Page';
            };
            $request['arguments'] = [];
        }

        return $request;
    }

    private static function convertUrl($url)
    {
        $url = rtrim($url, '/');

        return preg_replace_callback('/{[A-z0-9]+}/i', function () {
            return '(\w+)';
        }, $url);
    }

    private static function getRequestMethod()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && array_key_exists('_method', $_POST)) {
            return strtoupper($_POST['_method']);
        } else {
            return $_SERVER['REQUEST_METHOD'];
        }
    }

    public function middleware()
    {
        $middleware = is_array(func_get_arg(0)) ? func_get_arg(0) : func_get_args();

        self::$routes[$this->method][$this->route]['middleware'] = array_merge(
            self::$routes[$this->method][$this->route]['middleware'],
            $middleware
        );
    }

    public function home()
    {
        self::$home = $this->route;
    }
}
