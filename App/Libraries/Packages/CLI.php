<?php

namespace App\Libraries\Packages;


class CLI
{
    public static $list = [1 => "route list"];

    public static function switch_cli($opt)
    {
        $list_keys = array_keys(self::$list);
        if (in_array($opt, $list_keys)) {
            switch ($opt) {
                case 1:
                    self::router_list();
                    break;
            }
        } else {
            echo "\nYour request not founded";
        }
    }

    public static function router_list()
    {
        echo "\n\n";
        foreach (\App\Libraries\Router::$routes as $key => $val) {
            echo "{\n" . make_space(4) . "URL : " . rtrim(BASE_URL, '/') . (isset($val['attr']['prefix']) ? '/' : '') . @$val['attr']['prefix'] . $val['uri'] . "\n" . make_space(4) . "Methods : |";
            foreach ($val['methods'] as $met) {
                echo $met . "|";
            }
            if ($val['type'] == "controller") {
                echo "\n" . make_space(4) . "Type : " . $val['controller'] . "@" . $val['method'] . "\n}\n";
            } else {
                echo "\n" . make_space(4) . "Type : " . "Clouser\n}\n";
            }
        }
    }
}