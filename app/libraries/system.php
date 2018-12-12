<?php

require_once(APP_DIR . "Libraries" . SLASH . "Router.php");

use App\Libraries\Router;
use App\Config\HTTP;

function make_error(string $title = "", string $message = "")
{
    throw new Exception($title."CORE+EXP".$message);
}

function make_space(int $times)
{
    $str = "";
    if ($times <= 0)
        make_error("Times passed to make_space by a value less than 0 or 0", "Times parameter has to be more than 0");
    else {
        for ($i = 1; $i <= $times; $i++)
            $str .= " ";
    }
    return $str;
}

/**
 * @param $name
 * @return bool|string
 */
function uri_name(string $name)
{
    $GLOBALS['founded'] = false;
    foreach (Router::$routes as $route) {
        if (isset($route['attr']['name']) && $route['attr']['name'] == $name) {
            $GLOBALS['founded'] = true;
            if (isset($route['attr']['prefex'])) {
                return BASE_URL . $route['attr']['prefex'] . $route['uri'];
            }
            return rtrim(BASE_URL, '/') . $route['uri'];
        }
    }
    if (!$GLOBALS['founded'])
        return false;
}

function vd($experssion)
{
    var_dump($experssion);
    die();
}

function jd(array $expression)
{
    var_dump(json_encode($expression));
    die();
}

function string_processor($expression)
{
    return eval('return ' . $expression . ';');
}

function view(string $name, array $data = [], array $funcs = []): string
{
    $view = new \App\Libraries\Crash($name);
    foreach ($funcs as $func_name => $clouser) {
        $view->set_func($func_name, $clouser);
    }
    return $view->run($data);
}

function view_raw(string $file, array $data = [])
{
    $__ControllerPack = [];
    foreach ($data as $varname => $values) {
        $__ControllerPack[$varname] = $values;
    }
    if (file_exists(APP_DIR . 'HTTP' . SLASH . 'Views' . SLASH . $file . '.php')) {
        require_once APP_DIR . 'HTTP' . SLASH . 'Views' . SLASH . $file . '.php';
    } elseif (HTTP::VIEW_ERRORS)
        make_error("In Raw View " . APP_DIR . 'HTTP' . SLASH . 'Views' . SLASH . $file . '.crs.php' . " is not a file", "Directory " . APP_DIR . 'HTTP' . SLASH . 'Views' . SLASH . $file . '.php' . " does not exist.");
}

function view_boof(string $view, array $env = [], $layoutENV = [], array $funcs = [], string $starter = '{{', string $ender = '}}')
{
    $file = APP_DIR . 'HTTP' . SLASH . 'Views';
    $boof = new App\Libraries\Boof($file, '', $starter, $ender);
    foreach ($funcs as $func => $caller) {
        $boof->addFunction($funcs, $caller);
    }
    return $boof->view($view, $env, $layoutENV);
}

function redirect(string $to)
{
    \App\Libraries\Response::set_header("location:$to");
}

function url(string $sub_uri): string
{
    return rtrim(BASE_URL, '/') . $sub_uri;
}

