<?php

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
        if (HTTP::ADVANCED_ROUTING)
            $this->advanced_router();
        else
            $this->simple_router();
    }

    public function advanced_router()
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
        }
        if (!$founded) {
            http_response_code(404);
            if (defined('App\Config\HTTP::ERROR_404_PAGE_ADDR'))
                echo view_raw(HTTP::ERROR_404_PAGE_ADDR);
            else
                make_error("Your requested uri doesn't exists", "this page does not exists , please check uri");
        }
    }

    public function simple_router()
    {
        $controller_prefex = 'App\\HTTP\\Controllers\\';
        $payloder = $this->simple_router_commentator();
        $controller = $controller_prefex . $payloder['controller'] . 'Controller';
        $method = $payloder['method'];
        $params = $payloder['params'];
        array_unshift($params, new Request());
        if (class_exists($controller) && method_exists($controller, $method))
            echo call_user_func_array([new $controller, $method], $params);
        else {
            http_response_code(404);
            if (defined('App\Config\HTTP::ERROR_404_PAGE_ADDR'))
                echo view_raw(HTTP::ERROR_404_PAGE_ADDR);
            else
                make_error("Your requested uri doesn't exists", "this page does not exists , please check uri");
        }
    }

    public function simple_router_commentator()
    {
        $url = rtrim($this->check_uri(),'/');
        $paylod = array_slice(explode('/', $url), 1);
        $controller = (isset($paylod[0])) ? $paylod[0] : HTTP::SIMPLE_ROUTER_DEFULT_CONTROLLER;
        $method = (isset($paylod[1])) ? $paylod[1] : HTTP::SIMPLE_ROUTER_DEFULT_METHOD;
        $params = array_slice($paylod, 1);
        return ['controller' => $controller, 'method' => $method, 'params' => $params];
    }

    public function check_uri(): string
    {
        if (isset($_GET['url']))
            return '/' . rtrim($_GET['url'], '/');
        return '/';
    }
}