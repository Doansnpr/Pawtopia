<?php
require_once '../app/core/Database.php'; 
require_once '../app/models/BookingModel.php'; 
require_once '../app/models/StatusModel.php'; 

class DashboardMitra extends Controller { 
    
    protected $db; 
    
    public function __construct() {
        $db_instance = new Database(); 
        $this->db = $db_instance->getConnection(); 
    }
    
    public function index() {
        
        $current_page = $_GET['page'] ?? 'dashboard'; 
        
        $data = [
            'title' => 'Dashboard Mitra', 
            'content' => 'dashboard_mitra/dashboard_content' 
        ];
        
        
        if ($current_page === 'reservasi') {
            
            $bookingModel = new BookingModel($this->db); 
            $reservations = $bookingModel->getAllBookings();
            $statusCounts = $bookingModel->getStatusCounts();

            $data['title'] = 'Manajemen Reservasi';
            $data['reservations'] = $reservations;
            $data['statusCounts'] = $statusCounts;
            
            $data['content'] = 'dashboard_mitra/manajemen_booking/booking'; 
            
        } if ($current_page === 'status') {
            
            $statusModel = new StatusModel($this->db); 

            $data['title'] = 'Manajemen Status';
     
            
            $data['content'] = 'dashboard_mitra/manajemen_status_penitipan/status'; 
        }else if ($current_page === 'profil') {
            $data['title'] = 'Profil Mitra';
            $data['content'] = 'dashboard_mitra/profil_mitra/profil';
        } 
        
        $this->view('layouts/dashboard_layout', $data);
    }
    
}