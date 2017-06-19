<?php


namespace App\Http\Controllers;

use Meower\BaseController;

class HomeController extends BaseController
{
    public function index()
    {
        echo 'Hello World!';
    }

    public function show($id)
    {
        return view('index', ['id' => $id]);
    }
}
