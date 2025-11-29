<?php
require_once '../app/core/Database.php'; 
require_once '../app/models/BookingModel.php'; 

class DashboardMitra extends Controller { 
    
    protected $db; 
    
    public func
    
    $db_instance = new Database(); 
        $this->db = $db_instance->getConnection(); 
    }
    
    public function index() {
        
        $current_page = $_GET['page'] ?? 'dashboard'; 
        
        $data = [
            'title' => 'Dashboard Mitra', 
            'content' => 'dashboard_mitra/dashboard_content' 
        ];
        
        
        // 2. Logic Pindah Halaman
        if ($current_page === 'reservasi') {
            
            // --- LOGIC KHUSUS RESERVASI ---
            $bookingModel = new BookingModel($this->db); 
            $reservations = $bookingModel->getAllBookings();
            $statusCounts = $bookingModel->getStatusCounts();

            // Masukkan data reservasi ke array $data
            $data['title'] = 'Manajemen Reservasi';
            $data['reservations'] = $reservations;
            $data['statusCounts'] = $statusCounts;
            
            // Ganti view content ke file booking.php
            $data['content'] = 'dashboard_mitra/manajemen_booking/booking'; 
            
        } else if ($current_page === 'profil') {
            // ... Logic untuk halaman profil
            $data['title'] = 'Profil Mitra';
            $data['content'] = 'dashboard_mitra/profil_mitra/profil';
        } 
        
        // ... Tambahkan 'kucing', 'ulasan', 'laporan', dll.
        
        // 3. Panggil method view() dari core/Controller.php Anda
        // ASUMSI: view() hanya menerima 2 parameter: view (layout) dan data
        $this->view('layouts/dashboard_layout', $data);
    }
    
    // Anda dapat menambahkan method lain seperti 'terima_reservasi' di sini.
}