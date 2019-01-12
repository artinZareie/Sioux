<?php

require_once(__DIR__ . '/App/config/App.php');
require_once(APP_DIR . 'libraries' . SLASH . "system.php");
require_once(APP_DIR . "Bootstrap" . SLASH . "AutoLoader.php");
\App\Bootstrap\AutoLoader::make();
require_once(APP_DIR . "Config/routes.php");
require_once "App" . SLASH . "Libraries" . SLASH . "system.php";
require_once "App" . SLASH . "Config" . SLASH . "routes.php";
require_once "App" . SLASH . "Libraries" . SLASH . "Router.php";

$list = \App\Libraries\Packages\CLI::$list;
if (isset($argv))
    $args_list = array_slice($argv, 1);
else
    $args_list = [];


if (isset($args_list[0])) {
    $list_keys = array_keys($list);
    $opt = $args_list[0];
    if (in_array($opt, $list_keys)) {
        if ($opt == 1) {
            \App\Libraries\Packages\CLI::router_list();
        }
    } else {
        echo "\nYour request not founded";
    }
} else {
    echo "What are you looking for ? ";
    foreach ($list as $key => $obj) {
        echo "\n" . make_space(4) . $key . "." . $obj;
    }
    echo "\n" . make_space(4);

    $opt = 0;

    fscanf(STDIN, "%d\n", $opt); // reads number from STDIN

    $list_keys = array_keys($list);

    if (in_array($opt, $list_keys)) {
        \App\Libraries\Packages\CLI::router_list();
    } else {
        echo "\nYour request not founded";
    }
}