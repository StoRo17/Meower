<?php

namespace Meower\Core\Http;

class Route
{
    /**
     * Route pattern.
     * @var string
     */
    private $route;

    /**
     * HTTP method.
     * @var string
     */
    private $method;

    /**
     * @var string
     */
    public static $home;

    /**
     * Array of route patterns.
     * @var array
     */
    private static $routes;

    /**
     * Array of middlewares.
     * @var array
     */
    private static $middleware = [];

    /**
     * @var string
     */
    private static $prefix = '/';

    /**
     * Route constructor.
     * @param $route
     * @param $method
     */
    public function __construct($route, $method)
    {
        $this->route = $route;
        $this->method = $method;
    }

    /**
     * Wrapper of GET HTTP method.
     * @param $route
     * @param $callback
     * @return Route
     */
    public static function get($route, $callback)
    {   
        $route = self::generateRoute($route);

        self::add($route, $callback, 'GET');

        return new self($route, 'GET');
    }

    /**
     * Wrapper of POST HTTP method.
     * @param $route
     * @param $callback
     * @return Route
     */
    public static function post($route, $callback)
    {
        $route = self::generateRoute($route);

        self::add($route, $callback, 'POST');

        return new self($route, 'POST');
    }

    /**
     * Wrapper of PUT HTTP method.
     * @param $route
     * @param $callback
     * @return Route
     */
    public static function put($route, $callback)
    {
        $route = self::generateRoute($route);

        self::add($route, $callback, 'PUT');

        return new self($route, 'PUT');
    }

    /**
     * Wrapper of PATCH HTTP method.
     * @param $route
     * @param $callback
     * @return Route
     */
    public static function patch($route, $callback)
    {
        $route = self::generateRoute($route);

        self::add($route, $callback, 'PATCH');

        return new self($route, 'PATCH');
    }

    /**
     * Wrapper of DELETE HTTP method.
     * @param $route
     * @param $callback
     * @return Route
     */
    public static function delete($route, $callback)
    {
        $route = self::generateRoute($route);

        self::add($route, $callback, 'DELETE');

        return new self($route, 'DELETE');
    }

    /**
     * Group routes in callback with the same prefix or middleware(s).
     * @param $options
     * @param $callback
     */
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

    /**
     * @param $route
     * @return string
     */
    private static function generateRoute($route)
    {
        $route = str_replace('.', '/', $route);
        $route = trimBothSlashes($route);
        $route = self::$prefix . $route;

        return $route;
    }

    /**
     * Add all information about route to $routes array.
     * @param $route
     * @param $callback
     * @param $method
     */
    private static function add($route, $callback, $method)
    {
        self::$routes[$method][$route]['action'] = $callback;
        self::$routes[$method][$route]['middleware'] = self::$middleware;
    }

    /**
     * Find a request uri in $routes array and send it
     * @param Request $request
     * @return array
     */
    public static function dispatch($request)
    {
        require_once(APP_PATH . '/routes.php');

        $method = $request->method();
        $routes = self::$routes[$method];
        $urls = array_keys($routes);

        $url = rtrim(parse_url($request->url(), PHP_URL_PATH), '/') . '/';

        foreach ($urls as $routeUrl) {
            $preparedUrl = self::convertUrl($routeUrl);

            if (preg_match('#^' . $preparedUrl . '/{1}$#i', $url, $match)) {
                unset($match[0]);
                $route = $routes[$routeUrl];
                $route['arguments'] = array_values($match);
            }
        }

        if (!isset($route)) {
            $route['action'] = function () {
                echo '404 Page';
            };
            $route['arguments'] = [];
        }

        return $route;
    }

    /**
     * @param $url
     * @return string
     */
    private static function convertUrl($url)
    {
        $url = rtrim($url, '/');

        return preg_replace_callback('/{[A-z0-9]+}/i', function () {
            return '(\w+)';
        }, $url);
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
