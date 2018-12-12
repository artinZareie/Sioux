<?php

namespace App\Libraries;


interface CrudController
{
    public function index(Request $request);
    public function store(Request $request);
    public function destroy(Request $request, $id = '');
    public function edit(Request $request, $id = '');
}