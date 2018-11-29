<?php

namespace App\Config;


use App\HTTP\Middleware\CORS;

class HTTP
{
    public const MIDDLEWARES = [
//        \App\HTTP\Middleware\AgeMiddleware::class
    CORS::class
    ];

    public const VIEW_ERRORS = true;
}