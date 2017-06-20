<?php


namespace App\Http\Controllers;

class HomeController extends Controller
{
    public function index()
    {
        echo 'Hello World!';
    }

    public function show($id)
    {
        $renderedBody = $this->view->render('index', ['id' => $id]);
        return $this->di->response->body($renderedBody);
    }
}
