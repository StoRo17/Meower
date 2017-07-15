<?php

namespace App\Http\Middlewares;

use Closure;
use Meower\Core\Http\Request;
use Meower\Core\Http\Response;

class MyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Response $response
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, $response, $next)
    {
        if ($request->isMethod('get')) {
            return $response->redirect('/posts/1');
        }

        return $next($request, $response);
    }
}