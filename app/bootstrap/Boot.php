<?php

namespace App\Bootstrap;

require_once(__DIR__ . '/../config/App.php');
require_once(APP_DIR . 'libraries' . SLASH . "system.php");
require_once(APP_DIR . "Bootstrap" . SLASH . "AutoLoader.php");
require_once(APP_DIR."Config/routes.php");

class Boot
{
    public function __construct()
    {
        $this->autoload();
        new Core();
    }

    public function autoload()
    {
        AutoLoader::make();
    }
}