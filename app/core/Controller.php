<?php
class Controller {
    public function view($view, $data = [])
    {
        $viewPath = '../app/views/' . $view . '.php';
        require_once '../app/views/layouts/main.php';
    }


    public function model($model) {
        require_once "../app/models/" . $model . ".php";
        return new $model;
    }
}
