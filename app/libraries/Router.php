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
    public static $attr_groupes = [];

    private static function set_route(string $url, $response, array $methods, array $options = [])
    {
        if (is_callable($response)) {
            array_push(self::$routes,
                ["callable" => $response, "uri" => $url, "methods" => $methods, "type" => "callable"]
            );
        } elseif (is_string($response)) {
            if (preg_match("/[a-zA-Z0-9_]+\@[a-zA-Z0-9_]+/", $response)) {
                $exploded = explode('@', rtrim($response, '/'));
                if (!isset($exploded[0]) && !isset($exploded[1])) {
                    make_error("Route <code>" . $url . "</code> controller method has an error", "the controller method must matches with /[.]+\@[.]+/");
                } else {
                    $controller = $exploded[0];
                    $method = $exploded[1];
                    array_push(self::$routes,
                        ["controller" => $controller, "method" => $method, "uri" => $url, "methods" => $methods, "type" => "controller"]
                    );
                }
            } else {
                make_error("Route <code>" . $url . "</code> controller method has an error", "the controller method must matches with /[.]+\@[.]+/");
            }
        } else {
            make_error("Route is not matches with Callable or Controller Method", "It should be a Clouser or A controller method like Controller@method");
        }
    }

    public static function get(string $url, $response, array $settings = [])
    {
        self::set_route($url, $response, ["GET", "HEAD"], $settings);
    }

    public static function post(string $url, $response, array $settings = [])
    {
        self::set_route($url, $response, ["POST"]);
    }

    public static function put(string $url, $response, array $settings = [])
    {
        self::set_route($url, $response, ["PUT"], $settings);
    }

    public static function path(string $url, $response, array $settings = [])
    {
        self::set_route($url, $response, ["PATH"], $settings);
    }

    public static function options(string $url, $response, array $settings = [])
    {
        self::set_route($url, $response, ["PATH"], $settings);
    }

    public static function delete(string $url, $response, array $settings = [])
    {
        self::set_route($url, $response, ["DELETE"], $settings);
    }

    public static function all(string $url, $response, array $settings = [])
    {
        self::set_route($url, $response, ["GET", "POST", "PUT", "PATCH", "DELETE"]);
    }

    public static function matches(string $url, $response, array $methods, array $settings = [])
    {
        self::set_route($url, $response, $methods, $settings);
    }
}