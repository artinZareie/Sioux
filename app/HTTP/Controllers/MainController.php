<?php


namespace App\HTTP\Controllers;
use App\Libraries\Hash;

class MainController
{
    public function index($id)
    {
        return "Hello " . $id . " Guy";
    }
}