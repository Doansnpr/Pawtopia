<?php
require_once '../app/core/Database.php'; 
require_once '../app/models/BookingModel.php'; 

class BookingMitra extends Controller { 
    
    protected $db; 
    protected $bookingModel; 
    
    public function __construct() {
        
       $db_instance = new Database(); 
        $this->db = $db_instance->getConnection(); 

        $this->bookingModel = new BookingModel($this->db);
    }
    
    public function index() {
        $reservations = $this->bookingModel->getAllBookings();
        $statusCounts = $this->bookingModel->getStatusCounts();

        $data = [
            'title' => 'Manajemen Reservasi Mitra',
            'reservations' => $reservations,
            'statusCounts' => $statusCounts,
        ];
        
        $this->view('dashboard_mitra/manajemen_booking/booking', $data, 'dashboard_mitra_layout'); 
    }

    // Tambahkan method lain jika diperlukan, misal untuk aksi terima/tolak:
    /*
    public function terima($id_booking) {
        // ... Logika menerima reservasi ...
        header('Location: ' . BASEURL . '/BookingMitra');
    }
    */
}
