<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 11/8/2018
 * Time: 3:58 AM
 */

namespace App\Libraries;


class Router
{
    public static $routes = [];

    private static function set_route(string $url, callable $response, array $methods)
    {
        array_push(self::$routes,
            ["callable" => $response, "uri" => $url, "methods" => $methods]
        );
    }

    public static function get(string $url, callable $response)
    {
        self::set_route($url,$response,["GET"]);
    }

    public static function post(string $url, callable $response)
    {
        self::set_route($url,$response,["POST"]);
    }

    public static function put(string $url, callable $response)
    {
        self::set_route($url,$response,["PUT"]);
    }

    public static function path(string $url, callable $response)
    {
        self::set_route($url,$response,["PATH"]);
    }

    public static function delete(string $url, callable $response)
    {
        self::set_route($url,$response,["DELETE"]);
    }

    public static function all(string $url, callable $response)
    {
        self::set_route($url,$response,["GET","POST","PUT","PATCH","DELETE"]);
    }
}