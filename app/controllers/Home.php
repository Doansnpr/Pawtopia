<?php
class Home extends Controller {
    public function index() {
        $data['title'] = 'Halaman Utama Pawtopia';
        $this->view('home/index', $data);
    }
}
