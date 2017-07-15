<?php

namespace Meower\Core\Http;

class Middleware
{
    /**
     * @var array
     */
    private $routeMiddleware = [];

    /**
     * @var array
     */
    private $groupMiddleware = [];

    /**
     * Middleware constructor.
     */
    public function __construct()
    {
        $middlewares = require_once APP_PATH . '/config/middlewares.php';
        $this->routeMiddleware = $middlewares['routeMiddleware'];
        $this->groupMiddleware = $middlewares['groupMiddleware'];
    }

    /**
     * Find all needed middlewares which were specified in given route.
     *
     * @param array $middlewares
     * @return array
     */
    public function findNeededMiddleware($middlewares)
    {
        $neededMiddleware = [];
        foreach ($this->routeMiddleware as $middlewareName => $middlewareClass) {
            if (in_array($middlewareName, $middlewares)) {
                $neededMiddleware[] = new $middlewareClass();
            }
        }

        foreach ($this->groupMiddleware as $groupName => $middlewaresArr) {
            if (in_array($groupName, $middlewares)) {
                foreach ($middlewaresArr as $middlewareClass) {
                    $neededMiddleware[] = new $middlewareClass();
                }
            }
        }

        return $neededMiddleware;
    }
}
