<?php

namespace App\Libraries;

use App\Config\HTTP;


class Crash
{
    private $file;
    private $file_contents;
    private $data;

    public function __construct($file)
    {
        if (file_exists(APP_DIR . 'HTTP' . SLASH . 'Views' . SLASH . $file . '.crs.php')) {
            $this->file = APP_DIR . 'HTTP' . SLASH . 'Views' . SLASH . $file . '.crs.php';
            $this->file_contents = file_get_contents($this->file);
        } else
            make_error("In Crash template engine " . APP_DIR . 'HTTP' . SLASH . 'Views' . SLASH . $file . '.crs.php' . " is not a file", "Directory " . APP_DIR . 'HTTP' . SLASH . 'Views' . SLASH . $file . '.crs.php' . " does not exist.");
    }

    public function load($file)
    {
        $this->file = APP_DIR . 'HTTP' . SLASH . 'Views' . SLASH . $file . '.crs.php';
        $this->file_contents = file_get_contents($this->file);
    }

    public function run(...$data)
    {
        $this->data = $data;
        $this->do_vars();
    }

    private function do_vars()
    {
        preg_replace_callback('/\{\{(\S+)\}/', function ($matched) {
            if (isset($this->data[str_replace('{', '', str_replace('}', '', $matched[0]))]))
                return $this->data[$matched];
            elseif (HTTP::VIEW_ERRORS)
                make_error(str_replace('{', '', str_replace('}', '', $matched[0])) . " variable has not setted", "in " . $this->file);
            return "";
        }, $this->file_contents);
        vd($this->file_contents);
    }
}