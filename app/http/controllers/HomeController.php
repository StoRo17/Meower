<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    public function index()
    {
        return $this->response->body('Hello World');
    }

    public function show($id)
    {
        return $this->view('index', ['id' => $id]);
    }
}
