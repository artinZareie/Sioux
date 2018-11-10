<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 11/8/2018
 * Time: 4:05 AM
 */

namespace App\Bootstrap;

use App\Libraries\Router;


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
        foreach ($this->routes as $route) {
            $regex = '/^' . str_replace('/', '\/', $route['uri']) . '$/';
            $matches = [];
            if (preg_match($regex, $this->check_uri(), $matches) && in_array($_SERVER['REQUEST_METHOD'], $route['methods'])) {
                $matches = array_slice($matches, 1);
                if ($route['type'] == 'controller') {
                    $controller = 'App\\HTTP\\Controllers\\' . $route['controller'];
                    call_user_func_array([new $controller, $route['method']], $matches);
                    $founded = true;
                } elseif ($route['type'] == 'callable') {
                    call_user_func_array($route['callable'], $matches);
                    $founded = true;
                } else {
                    make_error("URL type is not valid", "URL (<code>" . $route['uri'] . "</code>) is not Clouser or a Controller Method , please solve it .");
                }
            }
        }
        if (!$founded) {
            http_response_code(404);
            make_error("Your requested uri doesn't exists", "this page does not exists , please check uri");
        }
    }

    public function check_uri(): string
    {
        if (isset($_GET['url']))
            return '/' . rtrim($_GET['url'],'/');
        return '/';
    }
}