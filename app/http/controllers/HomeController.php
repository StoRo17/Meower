<?php


namespace App\Http\Controllers;

use Meower\Controller;

class HomeController extends Controller
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
