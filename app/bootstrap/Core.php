<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 11/8/2018
 * Time: 4:05 AM
 */

namespace App\Bootstrap;

use App\Libraries\Request;
use App\Libraries\Router;
use App\Config\HTTP;


class Core
{
    private $routes = [];

    public function __construct()
    {
        $this->routes = Router::$routes;
        $this->make_url();
    }

    public function make_url()
    {
        $founded = false;
        $middleware_life = true;
        foreach ($this->routes as $route) {
            if (isset($route['attr']['prefix'])) {
                $regex = '/^' . str_replace('/', '\/', '/' . $route['attr']['prefix'] . $route['uri']) . '$/';
            } else {
                $regex = '/^' . str_replace('/', '\/', $route['uri']) . '$/';
            }
            $matches = [];
            if (preg_match($regex, $this->check_uri(), $matches) && in_array($_SERVER['REQUEST_METHOD'], $route['methods'])) {
                $matches = array_slice($matches, 1);
                foreach (array_merge(HTTP::MIDDLEWARES, isset($route['attr']['middlewares']) ? $route['attr']['middlewares'] : []) as $middleware) {
                    if (!$middleware::handler(new Request(), ...$matches)) {
                        echo $middleware::fails(new Request(), ...$matches);
                        $middleware_life = false;
                    }
                }
                if ($middleware_life) {
                    array_unshift($matches, new Request());
                    if ($route['type'] == 'controller') {
                        $controller = 'App\\HTTP\\Controllers\\' . $route['controller'];
                        echo call_user_func_array([new $controller, $route['method']], $matches);
                    } elseif ($route['type'] == 'callable') {
                        echo call_user_func_array($route['callable'], $matches);
                    } else {
                        make_error("URL type is not valid", "URL (<code>" . $route['uri'] . "</code>) is not Clouser or a Controller Method , please solve it .");
                    }
                }
                $founded = true;
            }
            if (!$founded) {
                http_response_code(404);
                make_error("Your requested uri doesn't exists", "this page does not exists , please check uri");
            }
        }
    }

    public function check_uri(): string
    {
        if (isset($_GET['url']))
            return '/' . rtrim($_GET['url'], '/');
        return '/';
    }
}