<?php


namespace App\HTTP\Controllers;

use App\Libraries\ControllerMother;
use App\Libraries\HPML;

//use App\Libraries\Request;

class MainController extends ControllerMother
{
    public function index()
    {

        HPML::register_style_by_file('public');
        HPML::set_registred_style_as_defult('public');
        HPML::make_head_tag(function (){return '';});
        return HPML::starter_template(function () {
            return HPML::tag('div', [], function () {
                return HPML::tag('p', ['style' => function () {
                    return 'font-family: "B Yekan", sans-serif;';
                }], function () {
                    return 'سلام دنیا !!!';
                });
            });
        }, ['dir' => 'rtl']);
    }
}