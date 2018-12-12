<?php

namespace App\Bootstrap;

require_once(__DIR__ . '/../config/App.php');
require_once(APP_DIR . 'libraries' . SLASH . "system.php");
require_once(APP_DIR . "Bootstrap" . SLASH . "AutoLoader.php");


class Boot
{
    public function __construct()
    {
        $this->autoload();
        require_once(APP_DIR . "Config" . SLASH . "routes.php");
        try {
            new Core();
        } catch (\Exception $e) {
            $title = explode("CORE+EXP", $e->getMessage())[0];
            $message = explode("CORE+EXP", $e->getMessage())[1];
            echo "<div class=\"error\" style=\"border-width:1px;border-style:solid;border-color:#ffb8af;border-radius:5px;background-color:#f8d7da;padding-top:1%;padding-bottom:1%;padding-right:2%;padding-left:2%;font-family:sans-serif;\" >
        <h3 style=\"color:#8e3456;font-weight:2000px;font-size:larger;\" >" . $title . ' by : ' . $e->getFile() . " line : " . $e->getLine() . "</h3>
        <hr style=\"color:#ffb8af;\" >
        <p class=\"error-text\" style=\"font-weight:100;color:#8e2f36;\" >" . $message . "<hr style=\"color:#ffb8af;\" >" . "</p>";
            foreach ($e->getTrace() as $item) {
                echo "<p class=\"error - text\" style=\"font - weight:100;color:#8e2f36;\" >follows in : " . @$item['file'] . (isset($item['line']) ? ' in line : ' : '') . @$item['line'] . ' in function : ' . @$item['class'] . (isset($item['class']) ? '::' : '') . @$item['function'] . '()</p> <br>';
                var_dump($item['args']);
            }
            echo '</div>';
        }
    }

    public function autoload()
    {
        AutoLoader::make();
    }
}