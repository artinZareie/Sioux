<?php

require_once(APP_DIR . "Libraries" . SLASH . "Router.php");

use App\Libraries\Router;

function make_error(string $title = "", string $message = "")
{
    die("<div class=\"error\" style=\"border-width:1px;border-style:solid;border-color:#ffb8af;border-radius:5px;background-color:#f8d7da;padding-top:1%;padding-bottom:1%;padding-right:2%;padding-left:2%;font-family:sans-serif;\" >
        <h3 style=\"color:#8e3456;font-weight:2000;font-size:larger;\" >$title</h3>
        <hr style=\"color:#ffb8af;\" >
        <p class=\"error-text\" style=\"font-weight:100;color:#8e2f36;\" >$message</p>
    </div>");
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

function vd($experssion) {
    var_dump($experssion);
    die();
}

function jd(array $expression) {
    var_dump(json_encode($expression));
    die();
}