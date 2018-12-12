<?php


namespace App\HTTP\Controllers;
use App\Libraries\ControllerMother;
use App\Libraries\DB;
use App\Libraries\Hash;
use App\Libraries\Request;

class MainController extends ControllerMother
{
    public function index(Request $request)
    {
        vd(DB::select('*', 'users'));
    }
}