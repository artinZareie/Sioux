<?php

namespace App\Libraries;

use App\Config\HTTP;


class Crash
{
    private $file;
    private $file_contents;
    private $data;
    private $funcs;
    private $voids = '';

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

    public function load_vm_funcs()
    {
        $this->replace_variables();
        $this->replace_array();
        $this->replace_functios();
    }

    public function run(array $data = [])
    {
        $this->data = $data;
        $this->load_vm_funcs();

        return $this->file_contents;
    }

    public function set_func(string $func_name, \Closure $func)
    {
        $this->funcs[$func_name] = $func;
    }

    private function replace_variables()
    {
        foreach ($this->data as $key => $value) {
            if (!is_array($value))
                $this->file_contents = preg_replace('/\{\{[ ]?(' . $key . ')[ ]?\}\}/', $value, $this->file_contents);
        }
    }

    private function replace_array()
    {
        foreach ($this->data as $key => $value) {
            if (is_array($value)) {
                $this->file_contents = preg_replace_callback('/\{\{[ ]?' . $key . '\.(\w+\.)(\w+)[ ]?\}\}/', function ($matches) use ($key) {
                    $payload = explode('.', $matches[1] . $matches[2]);
                    $last_var = $this->data[$key];
                    $get_err = false;
                    foreach ($payload as $value) {
                        if (isset($last_var[$value]))
                            $last_var = $last_var[$value];
                        elseif (HTTP::VIEW_ERRORS) {
                            make_error("the offset $value of array $key does not exist.", "in $this->file");
                            $get_err = true;
                        } else
                            $get_err = true;
                    }
                    if ($get_err)
                        return '';
                    else
                        return "$last_var";
                }, $this->file_contents);
            }
        }
    }

    private function replace_functios()
    {
        foreach ($this->funcs as $func_name => $func) {
            $this->file_contents = preg_replace_callback('/\{\{[ ]?'.$func_name.' (.+)[ ]?\}\}/', function ($matched) use($func_name, $func) {
                $params = explode(' ', rtrim($matched[1], ' '));
                foreach ($params as $key => $value) {
                    $params[$key] = string_processor($params[$key]);
                }
                return call_user_func_array($this->funcs[$func_name], $params);
            },$this->file_contents);
        }
    }
}