<?php

class Controller {

    public function view($view, $data = [], $layout = 'main') 
    {
        extract($data);
        
        $viewPath = '../app/views/' . $view . '.php'; 

        $layoutPath = '../app/views/layouts/' . $layout . '.php';

        if (!file_exists($viewPath)) {
            die("Error: View file not found at $viewPath");
        }
        if (!file_exists($layoutPath)) {
            die("Error: Layout file not found at $layoutPath");
        }
        
        require_once $layoutPath;
    }


    public function model($model) {
        require_once "../app/models/" . $model . ".php";
        return new $model;
    }
}
