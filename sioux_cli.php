<?php

require_once(__DIR__ . '/App/config/App.php');
require_once(APP_DIR . 'libraries' . SLASH . "system.php");
require_once(APP_DIR . "Bootstrap" . SLASH . "AutoLoader.php");
require_once(APP_DIR . "Config/routes.php");
require_once "App/Libraries/system.php";
require_once "App/Config/routes.php";
require_once "App/Libraries/Router.php";

$list = [1 => "route list"];
echo "What are you looking for ? ";
foreach ($list as $key => $obj) {
    echo "\n" . make_space(4) . $key . "." . $obj;
}
echo "\n" . make_space(4);

$opt = 0;

fscanf(STDIN, "%d\n", $opt); // reads number from STDIN

$list_keys = array_keys($list);

if (in_array($opt, $list_keys)) {
    if ($opt == 1) {
        echo "\n\n\n\n";
        foreach (\App\Libraries\Router::$routes as $key => $val) {
            echo "{\n" . make_space(4) . "URL : " . rtrim(BASE_URL, '/') . (isset($val) ? '/' : '') . @$val['attr']['prefix'] . $val['uri'] . "\n" . make_space(4) . "Methods : |";
            foreach ($val['methods'] as $met) {
                echo $met . "|";
            }
            if ($val['type'] == "controller") {
                echo "\n" . make_space(4) . "Type : " . $val['controller'] . "@" . $val['method'] . "\n}\n";
            }
            else {
                echo "\n" . make_space(4) . "Type : " . "Clouser\n}\n";
            }
        }
    }
} else {
    echo "\nYour request not founded";
}