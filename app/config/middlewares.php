<?php

return [
    'routeMiddleware' => [
    ],

    'groupMiddleware' => [
        'web' => [
            \App\Http\Middlewares\MyMiddleware::class,
        ],
    ],
];