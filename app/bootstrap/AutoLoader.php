<?php

namespace App\Bootstrap;


class AutoLoader
{
    public static function make()
    {
        spl_autoload_register("self::autoload");
    }

    public static function autoload($namespace)
    {
        if (file_exists(MAIN_DIR . $namespace . ".php")) {
            include_once MAIN_DIR . $namespace . ".php";
        } else {
            make_error("Class not founded !!!", "File <code>" . MAIN_DIR . $namespace . ".php</code> Not Exists , If you are sure that this file exist you should include it manualy , this error threw by auto loader on : <code>\$PROJECT_DIR\$\\App\\Bootstrap\\AutoLoader.php</code>");
        }
    }
}