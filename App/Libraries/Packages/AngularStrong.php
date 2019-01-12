<?php

namespace App\Libraries\Packages;


use \App\Libraries\Bazalt\Angular;
use App\Libraries\HPML;
use App\Libraries\Request;

class AngularStrong
{
    private $angular;
    private $app;

    public function __construct(string $file_name)
    {
        $this->angular = new Angular;
        $this->app = $this->angular->module('app', [
            'dir' => APP_DIR . SLASH . 'HTTP' . SLASH . 'Views',
            'file' => $file_name . '.html'
        ]);
        $this->set_system_directives();
        $this->bind('app', [
            'request' => (new Request())->all(),
            'styles' => HPML::front_get_all_registred_styles(),
            'defult_styles' => HPML::front_get_all_defult_registred_styles()
        ]);
        return $this;
    }

    private function set_system_directives()
    {
        $this->app->directive('ng-app', [
            'restrict' => 'A',
            'class' => Angular\Directive\NgApp::class
        ]);
        $this->app->directive('ng-model', [
            'restrict' => 'A',
            'class' => Angular\Directive\NgModel::class
        ]);
        $this->app->directive('ng-repeat', [
            'restrict' => 'A',
            'class' => Angular\Directive\NgRepeat::class
        ]);
        $this->app->directive('ng-include', [
            'restrict' => 'A',
            'class' => Angular\Directive\NgInclude::class
        ]);
        $this->app->directive('ng-if', [
            'restrict' => 'A',
            'class' => Angular\Directive\NgIf::class
        ]);
    }

    public function bind(string $name, $value)
    {
        $this->app->rootScope[$name] = $value;
        return $this;
    }

    public function set_directive(string $directive_name, array $options)
    {
        $this->app->directive($directive_name, $options);
        return $this;
    }

    public function get_app()
    {
        return $this->app;
    }

    public function render()
    {
        return $this->angular->bootstrap('app');
    }
}