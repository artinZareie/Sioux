<?php


namespace App\HTTP\Controllers;

use App\Libraries\ControllerMother;
use App\Libraries\Cookie;
use App\Libraries\HPML;
use App\Libraries\Packages\AngularStrong;
use App\Libraries\Request;
use App\Libraries\Session;


class MainController extends ControllerMother
{
    public function index(Request $request)
    {
        /*$view = new AngularStrong('index');
        return $view->bind('yourName', 'Artin')->bind('test', 'kdasd')->bind('listof', [
            [ 'title' => 'Item 1', 'show' => true ],
            [ 'title' => 'Item 2', 'show' => false ],
            [ 'title' => 'Item 3', 'show' => true ],
            [ 'title' => 'Item 4', 'show' => false ],
            [ 'title' => 'Item 5', 'show' => true ]
        ])->render();*/
        Cookie::set('artin', 'Artin');
        Cookie::set('pelang', 12);
        Cookie::hashing_off();
        vd(Cookie::all());
    }
}