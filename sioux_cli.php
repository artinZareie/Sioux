<?php

require_once(__DIR__ . '\\App\\config\\App.php');
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
echo "\n";

$opt = 0;

fscanf(STDIN, "%d\n", $opt); // reads number from STDIN

$list_keys = array_keys($list);

if (in_array($opt, $list_keys)) {
    if ($opt == 1) {
        foreach (\App\Libraries\Router::$routes as $key => $val) {
            echo "\n" . substr(BASE_URL, 0, strlen(BASE_URL) - 2) . $val['uri'] . " : |";
            foreach ($val['methods'] as $met) {
                echo make_space(8) . $met . "|";
            }
        }
    }
} else {
    echo "\nYour request not founded";
}