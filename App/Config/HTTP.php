<?php

namespace App\Config;


use App\HTTP\Middleware\CORS;

class HTTP
{
    public const MIDDLEWARES = [
//        \App\HTTP\Middleware\AgeMiddleware::class
        CORS::class
    ];

    public const VIEW_ERRORS = false;
    public const ADVANCED_ROUTING = true;
    public const SIMPLE_ROUTER_DEFULT_CONTROLLER = 'Main';
    public const SIMPLE_ROUTER_DEFULT_METHOD = 'index';
//    public const ERROR_404_PAGE_ADDR = 'errors' . SLASH . '404';
    public const USE_SESSIONS = true;
    public const HASH_COOKIES = true;

}