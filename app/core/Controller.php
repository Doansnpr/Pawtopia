<?php
class Controller {
    public function view($view, $data = []) {
        $viewPath = '../app/views/' . $view . '.php';

        if (file_exists($viewPath)) {
            require_once '../app/views/layouts/main.php';
        } else {
            die("View '$view' tidak ditemukan!");
        }
    }
    public function model($model) {
        require_once '../app/models/' . $model . '.php';
        return new $model;
    }
}
