<?php

class DashboardMitra extends Controller {
    public function index() {
        $data = [
            'title' => 'Dashboard Mitra'
        ];
        
        $this->view('dashboard_mitra/index', $data, 'dashboard_layout'); 
    }
}
