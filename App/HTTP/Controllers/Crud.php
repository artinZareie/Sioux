<?php

namespace App\HTTP\Controllers;


use App\Libraries\CrudController;
use App\Libraries\Request;

class Crud implements CrudController
{

    public function index(Request $request)
    {
        // TODO: Implement index() method.
        return 'Hello';
    }

    public function store(Request $request)
    {
        // TODO: Implement store() method.
        return 'store';
    }

    public function destroy(Request $request, $id = '')
    {
        // TODO: Implement destroy() method.
        return 'destroy';
    }

    public function edit(Request $request, $id = '')
    {
        // TODO: Implement edit() method.
        return 'edit';
    }
}