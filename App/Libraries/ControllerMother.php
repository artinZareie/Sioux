<?php

namespace App\Libraries;


abstract class ControllerMother
{
    private $model;
    private $model_name;

    public function load_model($model_name)
    {
        $model = 'App\\HTTP\\Models\\' . $model_name;
        if (class_exists($model)) {
            $this->model = new $model;
            $this->model_name = $model_name;
        } else
            make_error("model $model_name doesn't exists.");
    }

    public function model_func($func, ...$arguments)
    {
        if (method_exists($this->model, $func)) {
            $model_instance = $this->model;
            return $model_instance->{$func}(...$arguments);
        } else
            make_error("function $func does not exists in model " . $this->model_name);
    }
}