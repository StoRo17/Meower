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
        $renderedBody = $this->view->render('index', ['id' => $id]);
        return $this->response->body($renderedBody);
    }
}
